<?php

namespace App\Services\Requisitions;

use App\Constants\Accounts;
use App\Constants\ErrorMessages;
use App\Constants\TransactionType;
use App\Constants\WorkflowActions;
use App\Constants\WorkflowModules;
use App\Enums\RequisitionItemTypes;
use App\Enums\WorkflowProcessCodes;
use App\Events\JobCardCreated;
use App\Events\RequisitionRaised;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\MaterialReservationException;
use App\Exceptions\VehicleStateException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Requests\WorkShopManagement\SubmitJobCardToSupervisor;
use App\Http\Requests\WorkShopManagement\WorkshopMaterialResevationRequest;
use App\Http\Requests\WorkShopManagement\WorkshopRequisitionRequest;
use App\Http\Requests\WorkShopManagement\WorkshopServiceRequisitionRequest;
use App\Http\Requests\WorkShopManagement\WorkshopServiceReservationRequest;
use App\Models\MaterialDetail;
use App\Models\MaterialHeader;
use App\Models\VehicleManagement\VehicleHeader;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Models\WorkShopManagement\Mechanic;
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
    public function processJobCardMaterialRequisition(
        WorkshopRequisitionRequest $requisitionPostRequest
    ): JsonResponse
    {
        Log::info("Creating Workshop Material Request");

        DB::beginTransaction();

        $dateExpected = Carbon::parse($requisitionPostRequest->get("date_expected")) ?? Carbon::now()->addDays(7);
        $validFrom = Carbon::now();
        $registrationNumber = $requisitionPostRequest->get('vehicle_registration');

        /********************************************** Save Data **************************************/
        $user = Auth()->user();

        $this->validateVehicleStatus($registrationNumber);

        // check that each article selected is of correct class
        $item_type = "";
        $workflowProcess = "";

        switch ($requisitionPostRequest->get('itemType')) {
            case RequisitionItemTypes::STOCK_ITEM_CODE:
                $item_type = RequisitionItemTypes::STOCK_ITEM;
                $workflowProcess = WorkflowProcessCodes::StoresRequisition->value;
                break;
            case RequisitionItemTypes::NON_STOCK_ITEM_CODE:
                $item_type = RequisitionItemTypes::NON_STOCK_ITEM;
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

        $requisition_reference_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::WORKSHOP_REQUISITION);

        $form_order_number = null;
        switch ($requisitionPostRequest->get('itemType')) {
            case RequisitionItemTypes::STOCK_ITEM_CODE:
                $form_order_number =
                    DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::STOCK_REQUISITION);
                break;
            case RequisitionItemTypes::NON_STOCK_ITEM_CODE:
                $form_order_number =
                    DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::PURCHASE_REQUISITION);
                break;
        }


        Log::info("Doc No. " . $form_order_number);
        Log::info("Requisition Ref. " . $requisition_reference_number);
        Log::info("Determined Requisition Item Type Code " . $item_type);
        Log::info("Requisition Item Type " . $requisitionPostRequest->get("itemType"));

        $long_description = "Workshop Requisition Ref.No. " .
            $requisition_reference_number . " For Vehicle Reg No. " . $registrationNumber;
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

        $storeCode = $requisitionPostRequest->store_code;
        $jobCardNumber = $requisitionPostRequest->job_card_no;
        $workshopReference = $requisitionPostRequest->workshop_reference;
        $workshopCode = $requisitionPostRequest->get("workshop_code");

        $matHeader = MaterialHeader::create(
            [
                "created_by" => $user->id,
                "date_created" => Carbon::now(),
                "status" => StatusHelper::new(),
                "req_no" => $requisition_reference_number,
                "form_order" => $form_order_number,
                "workshop_no" => $workshopCode,
                "item_type" => $item_type,
                "requested_by" => $user->staff_no,
                "veh_reg_no" => $registrationNumber,
                "purchase_office" => $requisitionPostRequest->get("purchase_office"),
                "store" => $storeCode,
                "supplier_code" => $requisitionPostRequest->supplier,
                "valid_date_from" => $validFrom,
                "valid_date_to" => $dateExpected,
                "comments" => $requisitionPostRequest->remarks,
                "cost_assigned_to" => "CostCenter",
                "is_fuel" => "N",
                'document_no' => $jobCardNumber
            ]
        );

        WorkShopMaterialHeader::create(
            [
                "form_order" => $form_order_number,
                "job_card_no" => $jobCardNumber,
                "item_type_code" => $item_type_code,
                "workshop_reference" => $workshopReference,
                "workshop_code" => $workshopCode,
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
                "stores_code" => $storeCode,
                "supplier_code" => $requisitionPostRequest->supplier,
                "req_no" => $requisition_reference_number,
                "specifications" => $item["technical_specification"],
                "description" => $item["technical_specification"],
                "reg_no" => $item["registration"],
            ]);

            switch ($requisitionPostRequest->get('itemType')) {
                case RequisitionItemTypes::STOCK_ITEM_CODE:
                    WorkShopMaterial::create([
                        "wshp_act_code" => $workshopReference,
                        "workshop_code" => $workshopCode,
                        'sch_flouted' => 'N',
                        "form_order" => $form_order_number,
                        "evaluation" => "Y",
                        "date_mat" => Carbon::now(),
                        "mat_code" => $item["articleCode"],
                        "unit_of_measure" => $item["unit_of_measure"],
                        "quantity" => $item["quantity"],
                        "amount" => $item["total_price"],
                        "price" => $item["unit_price"],
                        "store_code" => $storeCode,
                        "supplier_code" => $requisitionPostRequest->get('supplier'),
                        "veh_reg_no" => $item["registration"],
                        "specifications" => $item["technical_specification"],
                        "requested_by" => $user->staff_no,
                        "requested_by_id" => $user->id,
                        "status" => StatusHelper::new(),
                        "created_by" => $user->staff_no,

                        // section
                        // "date_created" => Carbon::now(),
                        // defect_no
                        // proc_ref
                        // st_pur
                        // authorised_by
                        // "req_no" => $requisition_reference_number,
                        // "ind" => "Y",
                    ]);
                    break;
                case RequisitionItemTypes::NON_STOCK_ITEM_CODE:
                    WorkShopServiceModel::create([
                        "wshp_act_code" => $workshopReference,
                        "wshp_code" => $workshopCode,
                        "evaluation" => "Y",
                        "movt_no" => $form_order_number,
                        "date_send" => Carbon::now(),
                        "mat_code" => $item["articleCode"],
                        "unit_of_measure" => $item["unit_of_measure"],
                        "quantity" => $item["quantity"],
                        "amount_est" => (float)$item["quantity"] * (float)$item["unit_price"] ?? $item["total_price"],
                        "price" => $item["unit_price"],
                        "store_code" => $storeCode,
                        "code_office" => $requisitionPostRequest->get("purchase_office"),
                        "supp_code" => $requisitionPostRequest->get('supplier'),
                        "veh_reg_no" => $item["registration"],
                        "specifications" => $item["technical_specification"],
                        "originator" => $user->staff_no,
                        "requested_by_id" => $user->id,
                        "status" => StatusHelper::new(),
                        "created_by" => $user->staff_no
                    ]);
                    break;
            }
        }

        WorkShopComment::firstOrCreate(
            [
                "workshop_reference" => $workshopReference,
                "type" => "REQ",
            ],
            [
                "remarks" => $requisitionPostRequest->remarks,
                "status" => StatusHelper::new(),
                "created_by" => auth()->user()->staff_no
            ]);

        // Link Requisition and Job Card
        JobCardHeader::where("job_card_no", $jobCardNumber)
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
    public function processMaterialReservation(WorkshopMaterialResevationRequest $materialReservationRequest): JsonResponse
    {
        Log::info("Creating Workshop Material Booking");

        $validityTo = Carbon::parse($materialReservationRequest->get("date_expected")) ?? Carbon::now()->addDays(7);
        $validityFrom = Carbon::now();

        /********************************************** Save Data **************************************/
        $user = Auth()->user();

        $requestItemType = $materialReservationRequest->get('itemType');
        if ($requestItemType == RequisitionItemTypes::STOCK_ITEM_CODE) {
            $itemType = RequisitionItemTypes::STOCK_ITEM;
            $workflowProcess = WorkflowProcessCodes::StoresRequisition->value;
        } elseif ($requestItemType == RequisitionItemTypes::NON_STOCK_ITEM_CODE) {
            $itemType = RequisitionItemTypes::NON_STOCK_ITEM;
            $workflowProcess = WorkflowProcessCodes::PurchaseProcess->value;
        } else {
            throw new WorkflowTaskCreationFailedException("Article Item Type Is Missing");
        }

        $articles = config("tables.table_names.articles");

        $materials = $materialReservationRequest->get("items");

        $articlesMap = array();
        // check that each article selected is of correct class
        // check each article to make sure it's of the correct type and is no active on a reservation for the same car
        foreach ($materials as $item) {
            $registrationNumber = $item['registration'];
            $article = $item["articleCode"];
            $key = str_replace(
                    "_", "",
                    str_replace(" ", "", $registrationNumber)
                ) . str_replace("-", "", str_replace(" ", "", $article));

            // we've encountered the combination before - duplicate
            if (in_array($key, array_keys($articlesMap))) {
                $message = "Article $article has been already selected for vehicle $registrationNumber. Check your article";
                throw new MaterialReservationException($message);
            }

            $articlesMap[$key] = $registrationNumber;

            $this->validateVehicleStatus($registrationNumber);

            $query = DB::table("$articles");
            $item_type_code = $materialReservationRequest->get('itemType');

            $this->checkArticleGroup($item_type_code, $query, $itemType, $articles, $article, $registrationNumber);
        }

        DB::beginTransaction();

        // generate tms ref
        $requisition_reference_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::WORKSHOP_REQUISITION);

        $form_order_number = null;
        switch ($materialReservationRequest->get('itemType')) {
            case RequisitionItemTypes::STOCK_ITEM_CODE:
                $form_order_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::STOCK_REQUISITION);
                break;
            case RequisitionItemTypes::NON_STOCK_ITEM_CODE:
                $form_order_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::PURCHASE_REQUISITION);
                break;
        }

        Log::info("Reservation Ref. " . $requisition_reference_number);
        Log::info("Form Order. " . $form_order_number);
        Log::info("Reservation Item Type " . $materialReservationRequest->get("itemType"));
        Log::info("Determined Reservation Item Type Code " . $itemType);

        $short_description = "Workshop Reservation for Vehicles Reference $requisition_reference_number";
        $long_description = "Workshop Reservation Reference No. $requisition_reference_number For Vehicles";

        $this->workflowService->initiateWorkflowProcess(
            $requisition_reference_number,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $materialReservationRequest->get('remarks'),
            $user,
            $materialReservationRequest->total_amount ?? 0,
            $short_description,
            $long_description
        );

        $storeCode = $materialReservationRequest->get('store_code');
        $workshopCode = $materialReservationRequest->get("workshop_code");

        MaterialHeader::create(
            [
                "created_by" => $user->id,
                "date_created" => Carbon::now(),
                "status" => StatusHelper::new(),
                "req_no" => $requisition_reference_number,
                "form_order" => $form_order_number,
                "workshop_no" => $workshopCode,
                "item_type" => $itemType,
                "requested_by" => $user->staff_no,
                //"veh_reg_no" => $registrationNumber,
                "purchase_office" => $materialReservationRequest->get("purchase_office"),
                "store" => $storeCode,
                "supplier_code" => $materialReservationRequest->get('supplier'),
                "valid_date_from" => $validityFrom,
                "valid_date_to" => $validityTo,
                "comments" => $materialReservationRequest->get('remarks'),
                "cost_assigned_to" => "CostCenter",
                "is_fuel" => "N",
            ]
        );

        foreach ($materialReservationRequest->get("items") as $item) {
            MaterialDetail::create([
                "created_by" => $user->staff_no,
                "date_created" => Carbon::now(),
                "material_code" => $item["articleCode"],
                "unit_of_measure" => $item["unit_of_measure"],
                "quantity" => $item["quantity"],
                "amount" => $item["total_price"],
                "price" => $item["unit_price"],
                "stores_code" => $storeCode,
                "req_no" => $requisition_reference_number,
                "specifications" => $item["technical_specification"],
                "description" => $item["technical_specification"],
                "reg_no" => $item["registration"],
            ]);
        }

        WorkShopComment::firstOrCreate(
            [
                "workshop_reference" => $requisition_reference_number,
                "type" => "REQ",
            ],
            [
                "remarks" => $materialReservationRequest->remarks,
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
            case RequisitionItemTypes::SERVICE_ITEM_CODE:
            case RequisitionItemTypes::NON_STOCK_ITEM_CODE:
                $item_type = RequisitionItemTypes::SERVICE;
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
                case RequisitionItemTypes::STOCK_ITEM_CODE:
                    $query->where(function ($q) use ($item_type, $articles) {
                        $q->whereIn("$articles.code_group",
                            ["01", "04", "30"]);
                    });

                    break;
                case RequisitionItemTypes::NON_STOCK_ITEM_CODE:
                    $query->where(function ($q) use ($item_type, $articles) {
                        $q->where("$articles.code_group", "=", "40");
                    });

                    break;
                case RequisitionItemTypes::SERVICE_ITEM_CODE:
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
                $articleType = $item_type == RequisitionItemTypes::STOCK_ITEM
                    ? "Stock Item"
                    : ($item_type == RequisitionItemTypes::NON_STOCK_ITEM
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

        $storeCode = $requisitionPostRequest->store_code;
        $joCardNumber = $requisitionPostRequest->job_card_no;
        $workshopReference = $requisitionPostRequest->workshop_reference;
        $workshopCode = $requisitionPostRequest->get("workshop_code");

        $matHeader = MaterialHeader::create(
            [
                "created_by" => $user->id,
                "date_created" => Carbon::now(),
                "status" => StatusHelper::new(),
                "req_no" => $purchase_process_reference,
                "form_order" => $form_order,
                "workshop_no" => $workshopCode,
                "item_type" => $item_type,
                "requested_by" => $user->staff_no,
                "veh_reg_no" => $registrationNumber,
                "purchase_office" => $requisitionPostRequest->get("purchase_office"),
                "store" => $storeCode,
                "supplier_code" => $requisitionPostRequest->supplier,
                "valid_date_from" => $valid_from,
                "valid_date_to" => $valid_to,
                "comments" => $justification,
                "cost_assigned_to" => "CostCenter",
                "is_fuel" => "N",
                'document_no' => $joCardNumber
            ]
        );

        WorkShopMaterialHeader::create(
            [
                "form_order" => $form_order,
                "job_card_no" => $joCardNumber,
                "item_type_code" => $item_type_code,
                "workshop_reference" => $workshopReference,
                "workshop_code" => $workshopCode,
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
                "unit_of_measure" => $item["service_unit_of_measure"],
                "quantity" => $item["service_quantity"] ?? 1,
                "amount" => $item["service_total_price"],
                "price" => $item["service_unit_price"],
                "stores_code" => $storeCode,
                "req_no" => $purchase_process_reference,
                "specifications" => $item["service_technical_specification"],
                "description" => $item["service_technical_specification"],
                "reg_no" => $item["vehicle_registration"],
            ]);

            // Verified Og
            WorkShopServiceModel::create([
                "wshp_act_code" => $workshopReference,
                "wshp_code" => $workshopCode,
                "evaluation" => "Y",
                "movt_no" => $form_order,
                "date_send" => Carbon::now(),
                "mat_code" => $item["service_article"],
                "unit_of_measure" => $item["service_unit_of_measure"],
                "quantity" => $item["service_quantity"],
                "amount_est" => $item["service_total_price"],
                "price" => $item["service_unit_price"],
                "store_code" => $storeCode,
                "code_office" => $requisitionPostRequest->get("purchase_office"),
                "supp_code" => $requisitionPostRequest->supplier,
                "veh_reg_no" => $item["vehicle_registration"],
                "specifications" => $item["service_technical_specification"],
                "originator" => $user->staff_no,
                "requested_by_id" => $user->id,
                "status" => StatusHelper::new(),
                "created_by" => $user->id
            ]);
        }

        WorkShopComment::firstOrCreate(
            [
                "workshop_reference" => $workshopReference,
                "type" => "SREQ",
            ],
            [
                "remarks" => $requisitionPostRequest->remarks,
                "status" => StatusHelper::new(),
                "created_by" => auth()->user()->staff_no
            ]);

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

    /**
     * @throws VehicleStateException
     * @throws WorkflowTaskCreationFailedException
     * @throws MaterialReservationException
     */
    public function processServiceReservation(WorkshopServiceReservationRequest $serviceReservationRequest): JsonResponse
    {
        Log::info("Creating Workshop Service Booking");

        $periodFrom = Carbon::now();
        $periodTo = Carbon::now();
        /********************************************** Save Data **************************************/
        $user = Auth()->user();

        // check that each article selected is of correct class
        $itemType = "";
        $workflowProcess = "";

        if ($serviceReservationRequest->get('itemType') == RequisitionItemTypes::SERVICE_ITEM_CODE) {
            $itemType = RequisitionItemTypes::SERVICE;
            $workflowProcess = WorkflowProcessCodes::PurchaseProcess->value;
        }

        // check each article to make sure it's of the correct type and is no active on a reservation for the same car
        $articles = config("tables.table_names.articles");
        $serviceArticlesMap = array();
        foreach ($serviceReservationRequest->get("items") as $item) {
            $article = $item["service_article"];
            $registrationNumber = $item['vehicle_registration'];

            $this->validateVehicleStatus($registrationNumber);

            $itemTypeCode = $serviceReservationRequest->get('itemType');
            $key = str_replace("_", "", str_replace(" ", "", $registrationNumber))
                . str_replace("-", "", str_replace(" ", "", $article));

            if (in_array($key, array_keys($serviceArticlesMap))) {
                $message = "Article
                $article has been already selected for vehicle
                $registrationNumber. Check your article";
                throw new MaterialReservationException($message);
            }

            $serviceArticlesMap[$key] = $registrationNumber;

            $this->validateSelectedServiceArticles($articles,
                $itemTypeCode,
                $itemType,
                $item["service_article"],
                $registrationNumber);

        }

        $formOrder = DocumentNumberGenerationService::generateReferenceNumber(
            WorkflowModules::STOCK_REQUISITION
        );

        $purchaseProcessReference = DocumentNumberGenerationService::generateReferenceNumber(
            WorkflowModules::PURCHASE_REQUISITION
        );

        Log::info("Reservation Ref. $purchaseProcessReference");
        Log::info("Doc No.  $formOrder");
        Log::info("Reservation Item Type " . $serviceReservationRequest->get("itemType"));
        Log::info("Determined Reservation Item Type Code $itemType");

        $shortDescription = "Workshop Reservation Ref.No. $purchaseProcessReference";
        $longDescription = "Workshop Reservation Ref.No. $purchaseProcessReference";

        $this->workflowService->initiateWorkflowProcess(
            $purchaseProcessReference,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $serviceReservationRequest->remarks,
            $user,
            $serviceReservationRequest->total_amount ?? 0,
            $shortDescription,
            $longDescription
        );


        $workshopCode = $serviceReservationRequest->get("workshop_code");
        $storeCode = $serviceReservationRequest->get('store_code');

        MaterialHeader::create(
            [
                "created_by" => $user->id,
                "date_created" => Carbon::now(),
                "status" => StatusHelper::new(),
                "req_no" => $purchaseProcessReference,
                "form_order" => $formOrder,
                "workshop_no" => $workshopCode,
                "item_type" => $itemType,
                "requested_by" => $user->staff_no,
                //"veh_reg_no" => $registrationNumber,
                "purchase_office" => $serviceReservationRequest->get("purchase_office"),
                "store" => $storeCode,
                "supplier_code" => $serviceReservationRequest->get('supplier'),
                "valid_date_from" => $periodFrom,
                "valid_date_to" => $periodTo,
                "comments" => $serviceReservationRequest->get('remarks'),
                "cost_assigned_to" => "CostCenter",
                "is_fuel" => "N",
            ]
        );

        /*WorkShopServiceHeader::create(
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
                "unit_of_measure" => $item["service_unit_of_measure"],
                "quantity" => $item["service_quantity"] ?? 1,
                "amount" => $item["service_total_price"],
                "price" => $item["service_unit_price"],
                "stores_code" => $storeCode,
                "req_no" => $purchaseProcessReference,
                "specifications" => $item["service_technical_specification"],
                "description" => $item["service_technical_specification"],
                "reg_no" => $item["vehicle_registration"],
            ]);
        }

        WorkShopComment::firstOrCreate(
            [
                "workshop_reference" => $purchaseProcessReference,
                "type" => "SREQ",
            ],
            [
                "remarks" => $serviceReservationRequest->remarks,
                "status" => StatusHelper::new(),
                "created_by" => auth()->user()->staff_no
            ]);

        DB::commit();

        // send notification to Authoriser
        // RequisitionRaised::dispatch($matHeader);
        Log::info("Reservation " . $purchaseProcessReference . " raised successfully");

        return response()->json([
            "success" => true,
            "message" => "Reservation " . $purchaseProcessReference . " Generated and submitted to the next authority for Authorisation",
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
                $requisitionDetail->store,
                null
            );
        }


        if (empty($results)) {
            throw new FuelRequisitionException(ErrorMessages::getMessage('err_0022'));
        }

        if (!str_starts_with($results, "J02")) {
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
            ->where("item_type_code", "=", RequisitionItemTypes::NON_STOCK_ITEM_CODE)
            ->first();
        if (!empty($materialHeader)) {
            $results = $this->procurementService->createPurchaseProcess(
                $workshop_reference,
                $requisitionDetail->veh_reg_no,
                $requisitionDetail->form_order,
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

        if (!str_starts_with($results, "N01")) {
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
            ->where("item_type_code", "=", RequisitionItemTypes::SERVICE_ITEM_CODE)
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

        if (!str_starts_with($results, "N01")) {
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

    /**
     * @param mixed $jobCardNumber
     * @param $workShopActCode
     * @return Collection
     */
    public function getWorkShopRequisitionItems(mixed $jobCardNumber, $workShopActCode): Collection
    {
        $articles = config("tables.table_names.articles");
        return DB::table("WM_JOB_CARD_HEADER")
            ->join(
                "WM_WORKSHOP_MATERIALS mat",
                "WM_JOB_CARD_HEADER.WSHP_ACT_CODE",
                "=",
                "mat.WSHP_ACT_CODE"
            )
            ->leftJoin(
                "$articles",
                "mat.MAT_CODE",
                "=",
                "$articles.CODE_ARTICLE")
            ->where(
                "WM_JOB_CARD_HEADER.JOB_CARD_NO",
                "=",
                $jobCardNumber)
            ->whereNull("mat.IND")
            ->where("mat.evaluation", '=', "Y")
            ->select(
                "mat.*",
                "$articles.description as article_specification"
            )->get();
    }

    /**
     * @param mixed $workShopActCode
     * @return Collection
     */
    public function getWorkShopRequisitionServiceItems(mixed $workShopActCode): Collection
    {
        $articles = config("tables.table_names.articles");

        return DB::table('WM_WORKSHOP_SERVICES services')
            ->where("wshp_act_code", "=", $workShopActCode)
            ->leftJoin("$articles", "$articles.CODE_ARTICLE", "=", "services.mat_code")
            ->where(DB::raw("substr(services.mat_code, 0, 2)"), '=', '41')
            ->where("services.evaluation", '=', "Y")
            ->select(
                "services.*",
                "$articles.description as article_specification"
            )
            ->get();
    }

    public function getWorkShopRequisitionNonStockItems(mixed $workShopActCode): Collection
    {
        $articles = config("tables.table_names.articles");
        Log::debug('Loading Materials For Workshop Code' . $workShopActCode);
        return DB::table('WM_WORKSHOP_SERVICES services')
            ->where("wshp_act_code", "=", $workShopActCode)
            ->leftJoin("$articles", "$articles.CODE_ARTICLE", "=", "services.mat_code")
            ->where(DB::raw("substr(services.mat_code, 0, 2)"), '=', '40')
            ->where("services.evaluation", '=', "Y")
            ->select(
                "services.*",
                "$articles.description as article_specification"
            )
            ->get();
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
            case RequisitionItemTypes::STOCK_ITEM_CODE:
                $query->where(function ($q) use ($item_type, $articles) {
                    $q->whereIn("$articles.code_group",
                        ["01", "04", "30"]);
                });

                break;
            case RequisitionItemTypes::NON_STOCK_ITEM_CODE:
                $query->where(function ($q) use ($item_type, $articles) {
                    $q->where("$articles.code_group", "=", "40");
                });

                break;
            case RequisitionItemTypes::SERVICE_ITEM_CODE:
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
            $articleType = $item_type == RequisitionItemTypes::STOCK_ITEM
                ? "Stock Item"
                : ($item_type == RequisitionItemTypes::NON_STOCK_ITEM
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
     * @param mixed $itemTypeCode
     * @param string $itemType
     * @param $serviceArticle
     * @param mixed $registrationNumber
     * @return void
     * @throws MaterialReservationException
     */
    public function validateSelectedServiceArticles(
        mixed  $articles,
        mixed  $itemTypeCode,
        string $itemType,
               $serviceArticle,
        mixed  $registrationNumber): void
    {
        $query = DB::table("$articles");
        if ($itemTypeCode == RequisitionItemTypes::SERVICE_ITEM_CODE) {
            $query->where(function ($q) use ($itemType, $articles) {
                $q->where("$articles.code_group", "=", "41")
                    ->where("$articles.code_subgroup", "=", "02");
            });
        }

        $count = $query->where("code_article", "=", $serviceArticle)
            ->where("status", "=", "11")
            ->count();

        if ($count == 0) {
            $message = "Article @articleCode is not a @itemType";

            if ($itemType == RequisitionItemTypes::STOCK_ITEM) {
                $articleType = "Stock Item ";
            } elseif ($itemType == RequisitionItemTypes::NON_STOCK_ITEM) {
                $articleType = "Non Stock Item ";
            } else {
                $articleType = "Service ";
            }

            throw new MaterialReservationException(
                str_replace("@itemType", $articleType,
                    str_replace("@articleCode", $serviceArticle, $message)
                )
            );
        }

        $activeRequests = DB::table("gen_material_headers")
            ->join("gen_material_details",
                "gen_material_headers.req_no",
                "=",
                "gen_material_details.req_no")
            ->where("gen_material_details.material_code", "=", $serviceArticle)
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
                        str_replace("@articleCode", $serviceArticle, $message)
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
    public function createTaskForWorkShopSupervisor(SubmitJobCardToSupervisor $request): JsonResponse
    {
        DB::beginTransaction();
        $processCode = WorkflowProcessCodes::WorkOrderOpened->value;
        $user = auth()->user();

        $jobCardNo = $request->get('job_card_number');
        $registration = $request->get('vehicle_registration');
        $comments = $request->get('commentsToSupervisor');

        $jobCard = JobCardHeader::where("job_card_no", "=", $jobCardNo)
            ->first();

        $jobCard->step = 2;
        $jobCard->save();

        $workShopCode = $jobCard->workshop_code;

        $supervisor = Mechanic::where('workshop_code', '=', $workShopCode)
            ->where('is_supervisor', '=', 'Y')
            ->first();

        $workshopReference = $jobCardNo;
        $shortDescription = "New Job Card Task $jobCardNo For Vehicle $registration";
        $longDescription = $shortDescription;

        $this->workflowService->initiateWorkflowProcess(
            $workshopReference,
            (int)$processCode,
            WorkflowActions::submit(),
            $comments,
            $user,
            0,
            $shortDescription,
            $longDescription,
            $supervisor->staff_no ?? '71997'
        );

        DB::commit();

        JobCardCreated::dispatch($user, $supervisor, $jobCard);

        return response()->json([
            "success" => true,
            "message" => "Job Card Assignment Task Generated For $supervisor->name (Workshop Supervisor)",
            "redirectUrl" => URL::signedRoute("workOrder.list"),
        ]);
    }

    public function getPettyCashItems($reference): Collection
    {
        return DB::table("wm_imprest_buy_headers head")
            ->join(
                "wm_imprest_buy_details det",
                "head.imprest_reference",
                "=",
                "det.header_reference"
            )
            ->where(
                "head.work_order_number",
                "=",
                $reference)
            ->select(
                "det.*",
            )->get();
    }
}
