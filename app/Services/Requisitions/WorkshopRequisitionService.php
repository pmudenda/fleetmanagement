<?php

namespace App\Services\Requisitions;

use App\Constants\Accounts;
use App\Constants\ErrorMessages;
use App\Constants\TransactionType;
use App\Constants\WorkflowActions;
use App\Constants\WorkflowModules;
use App\Enums\RequisitionItemTypes;
use App\Enums\WorkflowProcessCodes;
use App\Events\RequisitionRaised;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\MaterialReservationException;
use App\Exceptions\VehicleStateException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Requests\WorkshopMaterialResevationRequest;
use App\Http\Requests\WorkshopRequisitionRequest;
use App\Http\Requests\WorkshopServiceRequisitionRequest;
use App\Http\Requests\WorkshopServiceReservationRequest;
use App\Models\MaterialDetail;
use App\Models\MaterialHeader;
use App\Models\VehicleManagement\VehicleHeader;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Models\WorkShopManagement\WorkShopComment;
use App\Models\WorkShopManagement\WorkShopMaterial;
use App\Models\WorkShopManagement\WorkShopMaterialHeader;
use App\Models\WorkShopManagement\WorkShopServiceModel;
use App\Services\Integration\ProcurementSystemIntegrationService;
use App\Services\VehicleManagement\VehicleDetailsService;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\Workflow\WorkflowService;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class WorkshopRequisitionService
{
    private VehicleDetailsService $vehicleDetailsService;
    private WorkflowService $workflowService;
    private ProcurementSystemIntegrationService $procurementService;

    public function __construct(VehicleDetailsService               $vehicleDetailsService,
                                WorkflowService                     $workflowService,
                                ProcurementSystemIntegrationService $procurementService)
    {
        $this->vehicleDetailsService = $vehicleDetailsService;
        $this->workflowService = $workflowService;
        $this->procurementService = $procurementService;
    }

    /**
     * Verifies Vehicle is Active otherwise throws exception
     * @param $reference
     * @return void
     * @throws VehicleStateException
     */
    public function validateVehicleStatus($reference): void
    {
        $allowedStatus = [StatusHelper::active(), StatusHelper::vehicleInWorkshop()];

        $vehicle = VehicleHeader::where("registration_number", "=", $reference)->first();

        if (empty($vehicle) || !in_array($vehicle->status, $allowedStatus)) {
            throw new VehicleStateException(ErrorMessages::getMessage("err_0004"), 1000);
        }
    }

    /**
     * @throws FuelRequisitionException|WorkflowTaskCreationFailedException|VehicleStateException|MaterialReservationException
     */
    public function processJobCardMaterialReservation(WorkshopRequisitionRequest $requisitionPostRequest): JsonResponse
    {
        Log::info("Creating Workshop Material Request");

        DB::beginTransaction();

        $valid_to = Carbon::parse($requisitionPostRequest->get("date_expected")) ?? Carbon::now()->addDays(7);
        $valid_from = Carbon::now();
        $registrationNumber = $requisitionPostRequest->vehicle_registration;

        /********************************************** Save Data **************************************/
        $user = Auth()->user();

        $this->validateVehicleStatus($registrationNumber);

        // check that each article selected is of correct class
        $item_type = "";
        $workflowProcess = "";

        switch ($requisitionPostRequest->itemType) {
            case RequisitionItemTypes::StockItemCode:
                $item_type = RequisitionItemTypes::StockItem;
                $workflowProcess = WorkflowProcessCodes::StoresRequisition->value;
                break;
            case RequisitionItemTypes::NonStockItemCode:
                $item_type = RequisitionItemTypes::NonStockItem;
                $workflowProcess = WorkflowProcessCodes::PurchaseProcess->value;
                break;
        }

        // check each article to make sure it's of the correct type and is no active on a reservation for the same car
        $articles = config("tables.table_names.articles");
        $articlesMap = array();
        foreach ($requisitionPostRequest->get("items") as $item) {

            $item_type_code = $requisitionPostRequest->itemType;

            $article = $item["articleCode"];

            $key = str_replace("_", "", str_replace(" ", "", $registrationNumber))
                . str_replace("-", "", str_replace(" ", "", $article));

            if (in_array($key, array_keys($articlesMap))) {
                $message = "Article $article has been already selected for vehicle $registrationNumber. Check your article";
                throw new MaterialReservationException($message);
            }

            $articlesMap[$key] = $registrationNumber;

            $query = DB::table("$articles");
            $this->checkArticleGroup($item_type_code, $query, $item_type, $articles, $article, $registrationNumber);

        }

        // generate  reference
        /*$requisition_reference_number = null;
        switch ($requisitionPostRequest->itemType) {
            case RequisitionItemTypes::StockItemCode:
                $requisition_reference_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::WORKSHOP_REQUISITION);
                break;
            case RequisitionItemTypes::NonStockItemCode:

                break;
        }*/
        $requisition_reference_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::PURCHASE_REQUISITION);

        $form_order_number = null;
        switch ($requisitionPostRequest->get('itemType')) {
            case RequisitionItemTypes::StockItemCode:
                $form_order_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::STOCK_REQUISITION);
                break;
            case RequisitionItemTypes::NonStockItemCode:
                $form_order_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::PURCHASE_REQUISITION);
                break;
        }


        Log::info("Doc No. " . $form_order_number);
        Log::info("Requisition Ref. " . $requisition_reference_number);
        Log::info("Determined Requisition Item Type Code " . $item_type);
        Log::info("Requisition Item Type " . $requisitionPostRequest->get("itemType"));

        $long_description = "Workshop Requisition Ref.No. " . $requisition_reference_number . " For Vehicle Reg No. " . $registrationNumber;
        $short_description = "Workshop Requisition for Vehicle Reg No. " . $registrationNumber;


        $this->workflowService->initiateWorkflowProcess(
            $requisition_reference_number,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $requisitionPostRequest->remarks,
            $user,
            $requisitionPostRequest->total_amount ?? 0,
            $short_description,
            $long_description
        );

        $store_code = $requisitionPostRequest->store_code;
        $job_cord_no = $requisitionPostRequest->job_card_no;
        $workshop_reference = $requisitionPostRequest->workshop_reference;
        $workshop_code = $requisitionPostRequest->get("workshop_code");

        $matHeader = MaterialHeader::create(
            [
                "created_by" => $user->id,
                "date_created" => Carbon::now(),
                "status" => StatusHelper::new(),
                "req_no" => $requisition_reference_number,
                "form_order" => $form_order_number,
                "workshop_no" => $workshop_code,
                "item_type" => $item_type,
                "requested_by" => $user->staff_no,
                "veh_reg_no" => $registrationNumber,
                "purchase_office" => $requisitionPostRequest->get("purchase_office"),
                "store" => $store_code,
                "supplier_code" => $requisitionPostRequest->supplier,
                "valid_date_from" => $valid_from,
                "valid_date_to" => $valid_to,
                "comments" => $requisitionPostRequest->remarks,
                "cost_assigned_to" => "CostCenter",
                "is_fuel" => "N",
            ]
        );


        WorkShopMaterialHeader::create(
            [
                "form_order" => $form_order_number,
                "job_card_no" => $job_cord_no,
                "item_type_code" => $item_type_code,
                "workshop_reference" => $workshop_reference,
                "workshop_code" => $workshop_code,
                "request_date" => Carbon::now(),
                "collection_date" => Carbon::parse($requisitionPostRequest->date_expected),
                "supplier_code" => $requisitionPostRequest->supplier,
                "purchasing_office" => $requisitionPostRequest->get("purchase_office"),
            ]);

        foreach ($requisitionPostRequest->get("items") as $item) {
            MaterialDetail::create([
                "created_by" => $user->staff_no,
                "date_created" => Carbon::now(),
                "material_code" => $item["articleCode"],
                "unit_of_measure" => $item["unit_of_measure"],
                "quantity" => $item["quantity"],
                "amount" => $item["total_price"],
                "price" => $item["unit_price"],
                "stores_code" => $store_code,
                "req_no" => $requisition_reference_number,
                "specifications" => $item["technical_specification"],
                "description" => $item["technical_specification"],
                "reg_no" => $item["registration"],
            ]);

            WorkShopMaterial::create([
                "wshp_act_code" => $workshop_reference,
                "workshop_code" => $workshop_code,
                // section
                // "date_created" => Carbon::now(),
                // defect_no
                // proc_ref
                // st_pur
                // authorised_by
                'sch_flouted' => 'N',
                // "req_no" => $requisition_reference_number,
                "form_order" => $form_order_number,
                "evaluation" => "Y",
                "date_mat" => Carbon::now(),
                "mat_code" => $item["articleCode"],
                "unit_of_measure" => $item["unit_of_measure"],
                "quantity" => $item["quantity"],
                "amount" => $item["total_price"],
                "price" => $item["unit_price"],
                "store_code" => $store_code,
                "ind" => "Y",
                "supplier_code" => $requisitionPostRequest->supplier,
                "veh_reg_no" => $item["registration"],
                "specifications" => $item["technical_specification"],
                "requested_by" => $user->staff_no,
                "requested_by_id" => $user->id,
                "status" => StatusHelper::new(),
                "created_by" => $user->id,
            ]);

        }

        WorkShopComment::firstOrCreate(
            [
                "workshop_reference" => $workshop_reference,
                "type" => "REQ",
            ],
            [
                "remarks" => $requisitionPostRequest->remarks,
                "status" => StatusHelper::new(),
                "created_by" => auth()->user()->staff_no
            ]);

        // Link Requisition and Job Card
        JobCardHeader::where("job_card_no", $job_cord_no)
            ->update(["req_no" => $requisition_reference_number]);


        DB::commit();

        // send notification to authoriser
        RequisitionRaised::dispatch($matHeader, 'requisition');
        Log::info("Requisition " . $requisition_reference_number . " raised successfully");

        return response()->json([
            "success" => true,
            "message" => "Requisition " . $requisition_reference_number . " Generated and submitted to the next authority for Authorisation",
            "redirectUrl" => URL::signedRoute("list.workshop.requisition"),
        ]);
    }

    /**
     * @throws VehicleStateException
     * @throws WorkflowTaskCreationFailedException
     * @throws MaterialReservationException
     */
    public function processMaterialReservation(WorkshopMaterialResevationRequest $materialResevationRequest): JsonResponse
    {
        Log::info("Creating Workshop Material Booking");

        $valid_to = Carbon::parse($materialResevationRequest->get("date_expected")) ?? Carbon::now()->addDays(7);
        $valid_from = Carbon::now();

        /********************************************** Save Data **************************************/
        $user = Auth()->user();

        $item_type = "";
        $workflowProcess = "";

        switch ($materialResevationRequest->get('itemType')) {
            case RequisitionItemTypes::StockItemCode:
                $item_type = RequisitionItemTypes::StockItem;
                $workflowProcess = WorkflowProcessCodes::StoresRequisition->value;
                break;
            case RequisitionItemTypes::NonStockItemCode:
                $item_type = RequisitionItemTypes::NonStockItem;
                $workflowProcess = WorkflowProcessCodes::PurchaseProcess->value;
                break;
        }

        $articles = config("tables.table_names.articles");

        $materials = $materialResevationRequest->get("items");

        $articlesMap = array();
        // check that each article selected is of correct class
        // check each article to make sure it's of the correct type and is no active on a reservation for the same car
        foreach ($materials as $item) {
            $registrationNumber = $item['registration'];
            $article = $item["articleCode"];

            $key = str_replace("_", "", str_replace(" ", "", $registrationNumber))
                . str_replace("-", "", str_replace(" ", "", $article));

            // we've encountered the combination before - duplicate
            if (in_array($key, array_keys($articlesMap))) {
                $message = "Article $article has been already selected for vehicle $registrationNumber. Check your article";
                throw new MaterialReservationException($message);
            }

            $articlesMap[$key] = $registrationNumber;

            $this->validateVehicleStatus($registrationNumber);

            $query = DB::table("$articles");
            $item_type_code = $materialResevationRequest->get('itemType');

            $this->checkArticleGroup($item_type_code, $query, $item_type, $articles, $article, $registrationNumber);
        }

        DB::beginTransaction();

        // generate tms ref
        $requisition_reference_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::WORKSHOP_REQUISITION);

        $form_order_number = null;
        switch ($materialResevationRequest->get('itemType')) {
            case RequisitionItemTypes::StockItemCode:
                $form_order_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::STOCK_REQUISITION);
                break;
            case RequisitionItemTypes::NonStockItemCode:
                $form_order_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::PURCHASE_REQUISITION);
                break;
        }

        Log::info("Reservation Ref. " . $requisition_reference_number);
        Log::info("Form Order. " . $form_order_number);
        Log::info("Reservation Item Type " . $materialResevationRequest->get("itemType"));
        Log::info("Determined Reservation Item Type Code " . $item_type);

        $short_description = "Workshop Reservation for Vehicles Reference $requisition_reference_number";
        $long_description = "Workshop Reservation Reference No. $requisition_reference_number For Vehicles";

        $this->workflowService->initiateWorkflowProcess(
            $requisition_reference_number,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $materialResevationRequest->get('remarks'),
            $user,
            $materialResevationRequest->total_amount ?? 0,
            $short_description,
            $long_description
        );

        $store_code = $materialResevationRequest->get('store_code');
        $workshop_code = $materialResevationRequest->get("workshop_code");

        MaterialHeader::create(
            [
                "created_by" => $user->id,
                "date_created" => Carbon::now(),
                "status" => StatusHelper::new(),
                "req_no" => $requisition_reference_number,
                "form_order" => $form_order_number,
                "workshop_no" => $workshop_code,
                "item_type" => $item_type,
                "requested_by" => $user->staff_no,
                //"veh_reg_no" => $registrationNumber,
                "purchase_office" => $materialResevationRequest->get("purchase_office"),
                "store" => $store_code,
                "supplier_code" => $materialResevationRequest->get('supplier'),
                "valid_date_from" => $valid_from,
                "valid_date_to" => $valid_to,
                "comments" => $materialResevationRequest->get('remarks'),
                "cost_assigned_to" => "CostCenter",
                "is_fuel" => "N",
            ]
        );

        /* WorkShopMaterialHeader::create(
             [
                 "form_order" => $form_order_number,
                 "job_card_no" => $job_cord_no,
                 "item_type_code" => $item_type_code,
                 "workshop_reference" => $workshop_reference,
                 "workshop_code" => $workshop_code,
                 "request_date" => Carbon::now(),
                 "collection_date" => Carbon::parse($materialResevationRequest->date_expected),
                 "supplier_code" => $materialResevationRequest->supplier,
                 "purchasing_office" => $materialResevationRequest->get("purchase_office"),
             ]);*/

        foreach ($materialResevationRequest->get("items") as $item) {
            MaterialDetail::create([
                "created_by" => $user->staff_no,
                "date_created" => Carbon::now(),
                "material_code" => $item["articleCode"],
                "unit_of_measure" => $item["unit_of_measure"],
                "quantity" => $item["quantity"],
                "amount" => $item["total_price"],
                "price" => $item["unit_price"],
                "stores_code" => $store_code,
                "req_no" => $requisition_reference_number,
                "specifications" => $item["technical_specification"],
                "description" => $item["technical_specification"],
                "reg_no" => $item["registration"],
            ]);

            /*   WorkShopMaterial::create([
                   "wshp_act_code" => $workshop_reference,
                   "workshop_code" => $workshop_code,
                   "form_order" => $form_order_number,
                   "evaluation" => "Y",
                   "date_mat" => Carbon::now(),
                   // "material_code" => $item["articleCode"],
                   "mat_code" => $item["articleCode"],
                   "unit_of_measure" => $item["unit_of_measure"],
                   "quantity" => $item["quantity"],
                   "amount" => $item["total_price"],
                   "price" => $item["unit_price"],
                   "store_code" => $store_code,
                   "ind" => "Y",
                   "supplier_code" => $materialResevationRequest->supplier,
                   "veh_reg_no" => $item["registration"],
                   "specifications" => $item["technical_specification"],
                   "requested_by" => $user->staff_no,
                   "requested_by_id" => $user->id,
                   "status" => StatusHelper::new(),
                   "created_by" => $user->id,
               ]);*/

        }

        WorkShopComment::firstOrCreate(
            [
                "workshop_reference" => $requisition_reference_number,
                "type" => "REQ",
            ],
            [
                "remarks" => $materialResevationRequest->remarks,
                "status" => StatusHelper::new(),
                "created_by" => auth()->user()->staff_no
            ]);

        DB::commit();

        //  send notification to authoriser
        //  RequisitionRaised::dispatch($matHeader);
        Log::info("Reservation Reference # " . $requisition_reference_number . " raised successfully");

        return response()->json([
            "success" => true,
            "message" => "Reservation " . $requisition_reference_number . " Submitted Successfully. Task generated for Authorisation",
            "redirectUrl" => URL::signedRoute("list.workshop.requisition"),
        ]);
    }

    /**
     * @throws VehicleStateException
     * @throws WorkflowTaskCreationFailedException
     * @throws MaterialReservationException
     */
    public function processServiceReservation(WorkshopServiceReservationRequest $serviceReservationRequest): JsonResponse
    {
        Log::info("Creating Workshop Service Booking");

        $valid_to = Carbon::now();
        $valid_from = Carbon::now();

        /********************************************** Save Data **************************************/
        $user = Auth()->user();

        // check that each article selected is of correct class
        $item_type = "";
        $workflowProcess = "";

        if ($serviceReservationRequest->get('itemType') == RequisitionItemTypes::ServiceItemCode) {
            $item_type = RequisitionItemTypes::Service;
            $workflowProcess = WorkflowProcessCodes::PurchaseProcess->value;
        }

        // check each article to make sure it's of the correct type and is no active on a reservation for the same car
        $articles = config("tables.table_names.articles");
        $serviceArticlesMap = array();
        foreach ($serviceReservationRequest->get("items") as $item) {
            $article = $item["service_article"];
            $registrationNumber = $item['vehicle_registration'];

            $this->validateVehicleStatus($registrationNumber);

            $item_type_code = $serviceReservationRequest->get('itemType');
            $key = str_replace("_", "", str_replace(" ", "", $registrationNumber))
                . str_replace("-", "", str_replace(" ", "", $article));

            if (in_array($key, array_keys($serviceArticlesMap))) {
                $message = "Article $article has been already selected for vehicle $registrationNumber. Check your article";
                throw new MaterialReservationException($message);
            }

            $serviceArticlesMap[$key] = $registrationNumber;

            $this->validateSelectedServiceArticles($articles, $item_type_code, $item_type, $item["service_article"], $registrationNumber);

        }

        $form_order = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::STOCK_REQUISITION);
        $purchase_process_reference = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::PURCHASE_REQUISITION);
        Log::info("Reservation Ref. $purchase_process_reference");
        Log::info("Doc No.  $form_order");
        Log::info("Reservation Item Type " . $serviceReservationRequest->get("itemType"));
        Log::info("Determined Reservation Item Type Code $item_type");

        $short_description = "Workshop Reservation Ref.No. $purchase_process_reference";
        $long_description = "Workshop Reservation Ref.No. $purchase_process_reference";

        $this->workflowService->initiateWorkflowProcess(
            $purchase_process_reference,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $serviceReservationRequest->remarks,
            $user,
            $serviceReservationRequest->total_amount ?? 0,
            $short_description,
            $long_description
        );

        $store_code = $serviceReservationRequest->get('store_code');
        $workshop_code = $serviceReservationRequest->get("workshop_code");

        MaterialHeader::create(
            [
                "created_by" => $user->id,
                "date_created" => Carbon::now(),
                "status" => StatusHelper::new(),
                "req_no" => $purchase_process_reference,
                "form_order" => $form_order,
                "workshop_no" => $workshop_code,
                "item_type" => $item_type,
                "requested_by" => $user->staff_no,
                //"veh_reg_no" => $registrationNumber,
                "purchase_office" => $serviceReservationRequest->get("purchase_office"),
                "store" => $store_code,
                "supplier_code" => $serviceReservationRequest->get('supplier'),
                "valid_date_from" => $valid_from,
                "valid_date_to" => $valid_to,
                "comments" => $serviceReservationRequest->get('remarks'),
                "cost_assigned_to" => "CostCenter",
                "is_fuel" => "N",
            ]
        );

        /*WorkShopMaterialHeader::create(
            [
                "form_order" => $form_order,
                "job_card_no" => $job_cord_no,
                "item_type_code" => $item_type_code,
                "workshop_reference" => $workshop_reference,
                "workshop_code" => $workshop_code,
                "request_date" => Carbon::now(),
                "collection_date" => Carbon::parse($serviceReservationRequest->date_expected),
                "supplier_code" => $serviceReservationRequest->supplier,
                "purchasing_office" => $serviceReservationRequest->get("purchase_office"),
            ]);*/

        foreach ($serviceReservationRequest->get("items") as $item) {

            MaterialDetail::create([
                "created_by" => $user->staff_no,
                "date_created" => Carbon::now(),
                "material_code" => $item["service_article"],
                //"mat_code" => $item["service_article"],
                "unit_of_measure" => $item["service_unit_of_measure"],
                "quantity" => $item["service_quantity"] ?? 1,
                "amount" => $item["service_total_price"],
                "price" => $item["service_unit_price"],
                "stores_code" => $store_code,
                "req_no" => $purchase_process_reference,
                "specifications" => $item["service_technical_specification"],
                "description" => $item["service_technical_specification"],
                "reg_no" => $item["vehicle_registration"],
            ]);

            /*WorkShopServiceModel::create([
                "workshop_reference" => $workshop_reference,
                "workshop_code" => $workshop_code,
                "req_evaluation" => "Y",
                // def_no
                // "movement_no",
                "date_send" => Carbon::now(),
                "material_code" => $item["service_article"],
                "unit_of_measure" => $item["service_unit_of_measure"],
                "quantity" => $item["service_quantity"],
                "amount_est" => $item["service_total_price"],
                //"price" => $item["service_unit_price"],
                "store_code" => $store_code,
                "office_code" => $serviceReservationRequest->get("purchase_office"),
                "ind" => "Y",
                // "stf_number",
                "supplier_code" => $serviceReservationRequest->supplier,
                "veh_reg_no" => $item["vehicle_registration"],
                "specification" => $item["service_technical_specification"],
                "originator" => $user->staff_no,
                "requested_by_id" => $user->id,
                "status" => StatusHelper::new(),
                "created_by" => $user->id,
                // "section",
                // "date_collect",
                // "authorised_by",
            ]);*/
        }

        WorkShopComment::firstOrCreate(
            [
                "workshop_reference" => $purchase_process_reference,
                "type" => "SREQ",
            ],
            [
                "remarks" => $serviceReservationRequest->remarks,
                "status" => StatusHelper::new(),
                "created_by" => auth()->user()->staff_no
            ]);

        DB::commit();

        // send notification to authoriser
        // RequisitionRaised::dispatch($matHeader);
        Log::info("Reservation " . $purchase_process_reference . " raised successfully");

        return response()->json([
            "success" => true,
            "message" => "Reservation " . $purchase_process_reference . " Generated and submitted to the next authority for Authorisation",
            "redirectUrl" => URL::signedRoute("list.workshop.requisition"),
        ]);
    }


    /**
     * @throws FuelRequisitionException
     */
    public function createWorkshopMaterialStoresReservation(mixed $req_no): mixed
    {
        $requisitionDetail = self::getReservationDetail($req_no);

        $materialHeader = WorkShopMaterialHeader::where("form_order", "=", $requisitionDetail->form_order)->first();

        if (!empty($materialHeader)) {
            $results = $this->procurementService->createStoresReservation(
                $req_no,
                $requisitionDetail->veh_reg_no,
                $requisitionDetail->form_order,
                Accounts::MOTOR_VEHICLE_MAINTENANCE_ACCOUNT,
                TransactionType::STORES_REQUISITIONS,
                $requisitionDetail->store,
                $materialHeader->job_card_no,
                $requisitionDetail->workshop_no
            );
        } else {
            $results = $this->procurementService->createStoresBookingReservation(
                $req_no,
                $requisitionDetail->veh_reg_no,
                $requisitionDetail->form_order,
                Accounts::MOTOR_VEHICLE_MAINTENANCE_ACCOUNT,
                TransactionType::STORES_REQUISITIONS,
                $requisitionDetail->store,
                null
            );
        }


        if (empty($results)) {
            throw new FuelRequisitionException(ErrorMessages::getMessage('err_0022'));
        }

        if (!str_contains($results, "J02")) {
            throw new FuelRequisitionException($results);
        }


        Log::info("Stores Reservation Generated with document " . $results);

        self::updateStPur($requisitionDetail->req_no, $results);

        return $results;
    }

    /**
     * @throws FuelRequisitionException
     */
    public function createWorkshopNonStockPurchaseProcess($workshop_reference): mixed
    {
        $requisitionDetail = self::getReservationDetail($workshop_reference);

        $materialHeader = WorkShopMaterialHeader::where("form_order", "=", $requisitionDetail->form_order)
            ->where("item_type_code", "=", RequisitionItemTypes::NonStockItemCode)
            ->first();
        if (!empty($materialHeader)) {
            $results = $this->procurementService->createPurchaseProcess(
                $workshop_reference,
                $requisitionDetail->veh_reg_no,
                $requisitionDetail->form_order,
                Accounts::MOTOR_VEHICLE_MAINTENANCE_ACCOUNT,
                TransactionType::SERVICE_PURCHASE_REQUISITIONS,
                $requisitionDetail->purchase_office,
                $materialHeader->job_card_no,
                $requisitionDetail->workshop_no
            );
        } else {
            $results = $this->procurementService->createPurchaseProcessBooking(
                $workshop_reference,
                $requisitionDetail->form_order
            );
        }

        if (empty($results)) {
            throw new FuelRequisitionException("Purchase Process Could Not Be Started ");
        }

        if (!str_contains($results, "N01")) {
            throw new FuelRequisitionException($results);
        }

        self::updateStPur($requisitionDetail->req_no, $results);

        Log::info("Purchase Process Document document " . $results);

        return $results;
    }

    /**
     * @throws FuelRequisitionException
     */
    public function createWorkshopServicePurchaseProcess(mixed $workshop_reference): mixed
    {
        $requisitionDetail = self::getReservationDetail($workshop_reference);

        $materialHeader = WorkShopMaterialHeader::where("form_order", "=", $requisitionDetail->form_order)
            ->where("item_type_code", "=", RequisitionItemTypes::ServiceItemCode)
            ->first();

        if (!empty($materialHeader)) {
            $results = $this->procurementService->createPurchaseProcess(
                $workshop_reference,
                $requisitionDetail->veh_reg_no,
                $requisitionDetail->form_order,
                Accounts::MOTOR_VEHICLE_MAINTENANCE_ACCOUNT,
                TransactionType::SERVICE_PURCHASE_REQUISITIONS,
                $requisitionDetail->purchase_office,
                $materialHeader->job_card_no,
                $requisitionDetail->workshop_no
            );
        } else {
            $results = $this->procurementService->createPurchaseProcessBooking(
                $workshop_reference,
                $requisitionDetail->form_order
            );
        }

        if (empty($results)) {
            throw new FuelRequisitionException("Purchase Process Could Not Be Started ");
        }

        if (!str_contains($results, "N01")) {
            throw new FuelRequisitionException($results);
        }

        self::updateStPur($requisitionDetail->req_no, $results);

        Log::info("Purchase Process Document " . $results);

        return $results;
    }


    public function getWorkShopReservationDetails(mixed $req_no): array
    {
        $articles = config("tables.table_names.articles");

        // "GEN_MATERIAL_HEADERS.*",
        $header = DB::table("GEN_MATERIAL_HEADERS")
            ->where("GEN_MATERIAL_HEADERS.req_no", $req_no)
            ->leftJoin("CONFIG_STATUSES", "GEN_MATERIAL_HEADERS.status", "=", "CONFIG_STATUSES.code")
            ->where("CONFIG_STATUSES.MODULE", "=", "MAT")
            ->select("GEN_MATERIAL_HEADERS.*", "CONFIG_STATUSES.name as status_name", "CONFIG_STATUSES.color_code")
            ->get();

        $detail = DB::table("GEN_MATERIAL_HEADERS")
            ->join("GEN_MATERIAL_DETAILS",
                "GEN_MATERIAL_HEADERS.req_no",
                "=",
                "GEN_MATERIAL_DETAILS.req_no")
            //->leftJoin("CONFIG_STATUSES", "GEN_MATERIAL_HEADERS.status", "=", "CONFIG_STATUSES.code")
            ->leftJoin("$articles", "GEN_MATERIAL_DETAILS.MATERIAL_CODE",
                "=", "$articles.CODE_ARTICLE")
            //->where("CONFIG_STATUSES.MODULE", "=", "MAT")
            ->where("GEN_MATERIAL_DETAILS.req_no", $req_no)
            ->select("GEN_MATERIAL_DETAILS.*",
                "$articles.description"
            //"CONFIG_STATUSES.name as status_name",
            //"CONFIG_STATUSES.color_code"
            )
            ->get();

        return [$header->first(), $detail];

    }

    public function getReservationDetail($req_no): mixed
    {
        $results = DB::table("GEN_MATERIAL_HEADERS")
            ->where("GEN_MATERIAL_HEADERS.req_no", $req_no)
            ->join("GEN_MATERIAL_DETAILS", "GEN_MATERIAL_HEADERS.req_no", "=", "GEN_MATERIAL_DETAILS.req_no")
            ->leftJoin("CONFIG_STATUSES", "GEN_MATERIAL_HEADERS.status", "=", "CONFIG_STATUSES.code")
            ->where("CONFIG_STATUSES.MODULE", "=", "MAT")
            ->select(
                "GEN_MATERIAL_HEADERS.*",
                "GEN_MATERIAL_DETAILS.*",
                "CONFIG_STATUSES.name as status_name",
                "CONFIG_STATUSES.color_code"
            )->get();

        return $results->first();

    }

    public function getWorkShopRequisitionItems(mixed $reference): Collection
    {
        return DB::table("WM_JOB_CARD_HEADER")
            ->join("WM_WORKSHOP_MATERIALS",
                "WM_JOB_CARD_HEADER.WSHP_ACT_CODE",
                "=",
                "WM_WORKSHOP_MATERIALS.WSHP_ACT_CODE"
            )
            ->where("WM_JOB_CARD_HEADER.JOB_CARD_NO", "=", $reference)
            ->select(
                "WM_WORKSHOP_MATERIALS.*"
            )->get();

    }

    /**
     * @throws WorkflowTaskCreationFailedException
     * @throws FuelRequisitionException
     * @throws MaterialReservationException|VehicleStateException
     */
    public function processJobCardServiceRequest(WorkshopServiceRequisitionRequest $requisitionPostRequest): JsonResponse
    {
        Log::info("Creating Workshop Service Request");

        $valid_to = Carbon::now(); //Carbon::parse($requisitionPostRequest->get("date_expected")) ?? Carbon::now()->addDays(7);
        $valid_from = Carbon::now();
        $registrationNumber = $requisitionPostRequest->vehicle_registration;

        /********************************************** Save Data **************************************/
        $user = Auth()->user();

        $this->validateVehicleStatus($registrationNumber);

        // check that each article selected is of correct class
        $item_type = "";
        $workflowProcess = "";

        switch ($requisitionPostRequest->itemType) {
            case RequisitionItemTypes::ServiceItemCode:
            case RequisitionItemTypes::NonStockItemCode:
                $item_type = RequisitionItemTypes::Service;
                $workflowProcess = WorkflowProcessCodes::PurchaseProcess->value;
                break;
        }

        // check each article to make sure it's of the correct type and is no active on a reservation for the same car
        //$stockManagement = config("tables.table_names.stockManagement");
        $articles = config("tables.table_names.articles");
        //$units = config("tables.table_names.units");

        foreach ($requisitionPostRequest->get("items") as $item) {
            $query = DB::table("$articles");
            $item_type_code = $requisitionPostRequest->itemType;

            switch ($item_type_code) {
                case RequisitionItemTypes::StockItemCode:
                    $query->where(function ($q) use ($item_type, $articles) {
                        $q->whereIn("$articles.code_group",
                            ["01", "04", "30"]);
                    });

                    break;
                case RequisitionItemTypes::NonStockItemCode:
                    $query->where(function ($q) use ($item_type, $articles) {
                        $q->where("$articles.code_group", "=", "40");
                    });

                    break;
                case RequisitionItemTypes::ServiceItemCode:
                    $query->where(function ($q) use ($item_type, $articles) {
                        $q->where("$articles.code_group", "=", "41")
                            ->where("$articles.code_subgroup", "=", "02");
                    });

                    break;
            }

            $count = $query
                ->where("code_article", "=", $item["service_article"])
                ->where("status", "=", "11")
                ->count();

            if ($count == 0) {
                $message = "Article @articleCode is not a @itemType";
                $articleType = $item_type == RequisitionItemTypes::StockItem
                    ? "Stock Item"
                    : ($item_type == RequisitionItemTypes::NonStockItem
                        ? "Non Stock Item " : "Service");

                throw new MaterialReservationException(
                    str_replace("@itemType", $articleType,
                        str_replace("@articleCode", $item["service_article"], $message)
                    )
                );
            }

            $activeRequests = DB::table("gen_material_headers")
                ->join("gen_material_details",
                    "gen_material_headers.req_no",
                    "=",
                    "gen_material_details.req_no")
                ->where("gen_material_details.material_code", "=", $item["service_article"])
                ->where("gen_material_details.reg_no", "=", $registrationNumber)
                ->whereIn("gen_material_headers.status", [
                    StatusHelper::new(),
                    StatusHelper::authorised(),
                    StatusHelper::partiallyReleased()
                ])->select("gen_material_headers.*")
                ->first();

            if (!empty($activeRequests)) {
                $message = "Article @articleCode is already on requisition/reservation @req_no for Vehicle @reg";
                throw new MaterialReservationException(
                    str_replace("@req_no", $activeRequests->req_no,
                        str_replace("@reg", $registrationNumber,
                            str_replace("@articleCode", $item["service_article"], $message)
                        ))
                );
            }

        }

        DB::beginTransaction();
        $form_order = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::STOCK_REQUISITION);
        $purchase_process_reference = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::PURCHASE_REQUISITION);

        Log::info("Requisition Ref. " . $purchase_process_reference);
        Log::info("Doc No. " . $form_order);
        Log::info("Requisition Item Type " . $requisitionPostRequest->get("itemType"));
        Log::info("Determined Requisition Item Type Code " . $item_type);

        $short_description = "Workshop Requisition for Vehicle Reg No. " . $registrationNumber;
        $long_description = "Workshop Requisition Ref.No. " . $purchase_process_reference . " For Vehicle Reg No. " . $registrationNumber;

        $justification = $requisitionPostRequest->remarks;

        $this->workflowService->initiateWorkflowProcess(
            $purchase_process_reference,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $justification,
            $user,
            $requisitionPostRequest->total_amount ?? 0,
            $short_description,
            $long_description
        );

        $store_code = $requisitionPostRequest->store_code;
        $job_cord_no = $requisitionPostRequest->job_card_no;
        $workshop_reference = $requisitionPostRequest->workshop_reference;
        $workshop_code = $requisitionPostRequest->get("workshop_code");

        $matHeader = MaterialHeader::create(
            [
                "created_by" => $user->id,
                "date_created" => Carbon::now(),
                "status" => StatusHelper::new(),
                "req_no" => $purchase_process_reference,
                "form_order" => $form_order,
                "workshop_no" => $workshop_code,
                "item_type" => $item_type,
                "requested_by" => $user->staff_no,
                "veh_reg_no" => $registrationNumber,
                "purchase_office" => $requisitionPostRequest->get("purchase_office"),
                "store" => $store_code,
                "supplier_code" => $requisitionPostRequest->supplier,
                "valid_date_from" => $valid_from,
                "valid_date_to" => $valid_to,
                "comments" => $justification,
                "cost_assigned_to" => "CostCenter",
                "is_fuel" => "N",
            ]
        );

        WorkShopMaterialHeader::create(
            [
                "form_order" => $form_order,
                "job_card_no" => $job_cord_no,
                "item_type_code" => $item_type_code,
                "workshop_reference" => $workshop_reference,
                "workshop_code" => $workshop_code,
                "request_date" => Carbon::now(),
                "collection_date" => Carbon::parse($requisitionPostRequest->date_expected),
                "supplier_code" => $requisitionPostRequest->supplier,
                "purchasing_office" => $requisitionPostRequest->get("purchase_office"),
            ]);

        foreach ($requisitionPostRequest->get("items") as $item) {

            MaterialDetail::create([
                "created_by" => $user->staff_no,
                "date_created" => Carbon::now(),
                "material_code" => $item["service_article"],
                //"mat_code" => $item["service_article"],
                "unit_of_measure" => $item["service_unit_of_measure"],
                "quantity" => $item["service_quantity"] ?? 1,
                "amount" => $item["service_total_price"],
                "price" => $item["service_unit_price"],
                "stores_code" => $store_code,
                "req_no" => $purchase_process_reference,
                "specifications" => $item["service_technical_specification"],
                "description" => $item["service_technical_specification"],
                "reg_no" => $item["vehicle_registration"],
            ]);

            WorkShopServiceModel::create([
                "wshp_act_code" => $workshop_reference,
                //"wshp_act_code" => $workshop_reference,
                "wshp_code" => $workshop_code,
                "req_evaluation" => "Y",
                // def_no
                // "movement_no",
                "movt_no" => $form_order,
                "date_send" => Carbon::now(),
                //"material_code" => $item["service_article"],
                "mat_code" => $item["service_article"],
                "unit_of_measure" => $item["service_unit_of_measure"],
                "quantity" => $item["service_quantity"],
                "amount_est" => $item["service_total_price"],
                //"price" => $item["service_unit_price"],
                "store_code" => $store_code,
                "office_code" => $requisitionPostRequest->get("purchase_office"),
                "ind" => "Y",
                // "stf_number",
                "supplier_code" => $requisitionPostRequest->supplier,
                "veh_reg_no" => $item["vehicle_registration"],
                "specification" => $item["service_technical_specification"],
                "originator" => $user->staff_no,
                "requested_by_id" => $user->id,
                "status" => StatusHelper::new(),
                "created_by" => $user->id,
                // "section",
                // "date_collect",
                // "authorised_by",
            ]);
        }

        WorkShopComment::firstOrCreate(
            [
                "workshop_reference" => $workshop_reference,
                "type" => "SREQ",
            ],
            [
                "remarks" => $requisitionPostRequest->remarks,
                "status" => StatusHelper::new(),
                "created_by" => auth()->user()->staff_no
            ]);

        // Link Requisition and Job Card
        /*JobCardHeader::where("job_card_no", $job_cord_no)
            ->update(["req_no" => $purchase_process_reference]);*/


        DB::commit();

        // send notification to authoriser
        RequisitionRaised::dispatch($matHeader, 'job_card_material_requisition');
        Log::info("Requisition " . $purchase_process_reference . " raised successfully");

        return response()->json([
            "success" => true,
            "message" => "Requisition " . $purchase_process_reference . " Generated and submitted to the next authority for Authorisation",
            "redirectUrl" => URL::signedRoute("list.workshop.requisition"),
        ]);
    }


    public function updateStatus(mixed $reference, string $status): void
    {
        MaterialHeader::where("req_no", $reference)
            ->update(["status" => $status]);
    }

    public function updateStPur(mixed $reference, string $stPur): void
    {
        DB::beginTransaction();
        MaterialHeader::where("req_no", $reference)
            ->update(["st_pur" => $stPur, "proc_ref" => $stPur]);
        DB::commit();
    }

    /**
     * @param mixed $item_type_code
     * @param Builder $query
     * @param string $item_type
     * @param mixed $articles
     * @param $articleCode
     * @param mixed $registrationNumber
     * @return void
     * @throws MaterialReservationException
     */
    public function checkArticleGroup(mixed $item_type_code, Builder $query, string $item_type, mixed $articles, $articleCode, mixed $registrationNumber): void
    {
        switch ($item_type_code) {
            case RequisitionItemTypes::StockItemCode:
                $query->where(function ($q) use ($item_type, $articles) {
                    $q->whereIn("$articles.code_group",
                        ["01", "04", "30"]);
                });

                break;
            case RequisitionItemTypes::NonStockItemCode:
                $query->where(function ($q) use ($item_type, $articles) {
                    $q->where("$articles.code_group", "=", "40");
                });

                break;
            case RequisitionItemTypes::ServiceItemCode:
                $query->where(function ($q) use ($item_type, $articles) {
                    $q->where("$articles.code_group", "=", "41");
                });

                break;
        }

        $count = $query
            ->where("code_article", "=", $articleCode)
            ->where("status", "=", "11")
            ->count();

        // article not found in the item type class
        if ($count == 0) {
            $message = "Article @articleCode is not a @itemType";
            $articleType = $item_type == RequisitionItemTypes::StockItem
                ? "Stock Item"
                : ($item_type == RequisitionItemTypes::NonStockItem
                    ? "Non Stock Item " : "Service");

            throw new MaterialReservationException(
                str_replace("@itemType", $articleType,
                    str_replace("@articleCode", $articleCode, $message)
                )
            );
        }

        $activeRequests = DB::table("gen_material_headers")->join("gen_material_details",
            "gen_material_headers.req_no",
            "=",
            "gen_material_details.req_no")
            ->where("gen_material_details.material_code", "=", $articleCode)
            ->where("gen_material_details.reg_no", "=", $registrationNumber)
            ->whereIn("gen_material_headers.status", [
                StatusHelper::new(),
                StatusHelper::authorised(),
                StatusHelper::partiallyReleased()
            ])->select("gen_material_headers.*")
            ->first();

        if (!empty($activeRequests)) {
            $message = "Article @articleCode is already on requisition/reservation @req_no for Vehicle @reg";
            throw new MaterialReservationException(
                str_replace("@req_no", $activeRequests->req_no,
                    str_replace("@reg", $registrationNumber,
                        str_replace("@articleCode", $articleCode, $message)
                    ))
            );
        }
    }

    /**
     * @param mixed $articles
     * @param mixed $item_type_code
     * @param string $item_type
     * @param $service_article
     * @param mixed $registrationNumber
     * @return void
     * @throws MaterialReservationException
     */
    public function validateSelectedServiceArticles(mixed $articles, mixed $item_type_code, string $item_type, $service_article, mixed $registrationNumber): void
    {
        $query = DB::table("$articles");
        if ($item_type_code == RequisitionItemTypes::ServiceItemCode) {
            $query->where(function ($q) use ($item_type, $articles) {
                $q->where("$articles.code_group", "=", "41")
                    ->where("$articles.code_subgroup", "=", "02");
            });
        }

        $count = $query->where("code_article", "=", $service_article)
            ->where("status", "=", "11")
            ->count();

        if ($count == 0) {
            $message = "Article @articleCode is not a @itemType";
            $articleType = $item_type == RequisitionItemTypes::StockItem
                ? "Stock Item"
                : ($item_type == RequisitionItemTypes::NonStockItem
                    ? "Non Stock Item " : "Service");

            throw new MaterialReservationException(
                str_replace("@itemType", $articleType,
                    str_replace("@articleCode", $service_article, $message)
                )
            );
        }

        $activeRequests = DB::table("gen_material_headers")
            ->join("gen_material_details",
                "gen_material_headers.req_no",
                "=",
                "gen_material_details.req_no")
            ->where("gen_material_details.material_code", "=", $service_article)
            ->where("gen_material_details.reg_no", "=", $registrationNumber)
            ->whereIn("gen_material_headers.status", [
                StatusHelper::new(),
                StatusHelper::authorised(),
                StatusHelper::partiallyReleased()
            ])->select("gen_material_headers.*")
            ->first();

        if (!empty($activeRequests)) {
            $message = "Article @articleCode is already on requisition/reservation @req_no for Vehicle @reg";
            throw new MaterialReservationException(
                str_replace("@req_no", $activeRequests->req_no,
                    str_replace("@reg", $registrationNumber,
                        str_replace("@articleCode", $service_article, $message)
                    ))
            );
        }
    }

    public function updateMaterialHeaderStatus(mixed $reference, string $status)
    {
        /*DB::beginTransaction();
        MaterialHeader::where("req_no", $reference)
            ->update(["status" => $status]);
        DB::commit();*/
    }

    /**
     * @throws WorkflowTaskCreationFailedException
     */
    public function createTaskForWorkShopSupervisor(Request $request): JsonResponse
    {
        $process_code = WorkflowProcessCodes::WorkOrderOpened->value;
        $user = auth()->user();

        $short_description = "New Job Task $request->get('job_card_number')";
        $long_description = "New Job Task $request->get('job_card_number') For Vehicle $request->get('vehicle_registration')";

        $this->workflowService->initiateWorkflowProcess(
            $request->get('job_card_number'),
            (int)$process_code,
            WorkflowActions::submit(),
            $request->get('commentsToSupervisor'),
            $user,
            0,
            $short_description,
            $long_description
        );

        return response()->json([
            "success" => true,
            "message" => "Job Card Task Generated For Workshop Supervisor",
            "redirectUrl" => URL::signedRoute("list.workshop.requisition"),
        ]);
    }
}
