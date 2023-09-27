<?php

namespace App\Services\WorkShopManagement;

use App\Constants\Accounts;
use App\Constants\Articles;
use App\Constants\ErrorMessages;
use App\Constants\QueryComparisonOperator;
use App\Constants\SystemMessages;
use App\Constants\ValidationProcess;
use App\Constants\WorkflowActions;
use App\Constants\WorkflowModules;
use App\Enums\RequisitionItemTypes;
use App\Enums\WorkflowProcessCodes;
use App\Events\MaterialReservationMade;
use App\Events\RequisitionRaised;
use App\Exceptions\DuplicateArticleException;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\InvalidArticleTypeException;
use App\Exceptions\MaterialReservationException;
use App\Exceptions\ServiceRequisitionException;
use App\Exceptions\VehicleStateException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Requests\WorkShopManagement\MaterialReservationRequest;
use App\Http\Requests\WorkShopManagement\WorkshopRequisitionRequest;
use App\Http\Requests\WorkShopManagement\WorkshopServiceRequisitionRequest;
use App\Http\Requests\WorkShopManagement\WorkshopServiceReservationRequest;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\MaterialDetail;
use App\Models\MaterialHeader;
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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;


class WorkshopRequisitionService
{
    const APPROVED = 'Request Approved and Submitted to the Next Authority For Approval ';
    private VehicleDetailsService $vehicleDetailsService;
    private WorkflowService $workflowService;
    private ProcurementSystemIntegrationService $procurementService;
    private MaterialValidationService $materialValidationService;
    private MaterialDetailService $materialDetailService;

    public function __construct(VehicleDetailsService               $vehicleDetailsService,
                                WorkflowService                     $workflowService,
                                ProcurementSystemIntegrationService $procurementService,
                                MaterialValidationService           $materialValidationService,
                                MaterialDetailService               $materialDetailService)
    {
        $this->vehicleDetailsService = $vehicleDetailsService;
        $this->workflowService = $workflowService;
        $this->procurementService = $procurementService;
        $this->materialValidationService = $materialValidationService;
        $this->materialDetailService = $materialDetailService;
    }


    /**
     * @throws
     * |WorkflowTaskCreationFailedException
     * |VehicleStateException|MaterialReservationException
     */
    public function processJobCardMaterialRequisition(WorkshopRequisitionRequest $requisitionPostRequest): JsonResponse
    {
        Log::info("Creating Workshop Material Request");

        $dateExpected = Carbon::parse($requisitionPostRequest->get("date_expected")) ?? Carbon::now()->addDays(7);
        $validFrom = Carbon::now();
        $registrationNumber = $requisitionPostRequest->get('vehicle_registration');

        Log::info('********************************* Save Data **********************************');

        $this->vehicleDetailsService->verifyVehicleIsActive($registrationNumber);

        // check that each article selected is of correct class
        $requestItemType = $requisitionPostRequest->get('itemType');

        list($articleClass, $workflowProcess) = $this->getArticleClass($requestItemType);

        $this->materialValidationService->validateArticle(
            $requisitionPostRequest,
            $registrationNumber,
            $articleClass,
            ValidationProcess::ARTICLE_FIELD,
            ValidationProcess::OTHER
        );

        $user = auth()->user();
        list($requisition_reference_number, $matHeader) = $this->saveJobCardMaterialRequest(
            $registrationNumber,
            $workflowProcess,
            $requisitionPostRequest,
            $user,
            $articleClass,
            $validFrom,
            $dateExpected
        );

        // send notification
        RequisitionRaised::dispatch($matHeader, 'requisition');
        Log::info("Material Requisition  submitted successfully $requisition_reference_number");

        return response()->json(
            FleetMasterJsonResponse::response(
                'success',
                true,
                str_replace(
                    '@req',
                    $requisition_reference_number,
                    SystemMessages::REQUISITION_SUCCESSFUL
                ),
                null,
                URL::signedRoute("list.workshop.requisition")
            )
        );
    }

    /**
     * @throws VehicleStateException
     * @throws WorkflowTaskCreationFailedException
     * @throws MaterialReservationException
     * @throws InvalidArticleTypeException
     */
    public function processMaterialReservation(MaterialReservationRequest $materialReservationRequest): JsonResponse
    {
        Log::info("Creating Workshop Material Booking");

        $validityTo = Carbon::parse($materialReservationRequest->get("date_expected"))
            ?? Carbon::now()->addDays(7);
        $validityFrom = Carbon::now();

        /********************************************** Save Data **************************************/
        $user = Auth()->user();

        $requestItemType = $materialReservationRequest->get('itemType');
        Log::debug("Reservation Article Type " . $requestItemType);

        if (!in_array($requestItemType,
            [RequisitionItemTypes::STOCK_ITEM_CODE, RequisitionItemTypes::NON_STOCK_ITEM_CODE])) {
            throw new WorkflowTaskCreationFailedException("Article Item Type Is Missing");
        }

        list($articleClass, $workflowProcess) = $this->getArticleClass($requestItemType);

        Log::debug("Determined Article Class " . $articleClass);

        $articlesTable = config("tables.table_names.articles");

        $materials = $materialReservationRequest->get("items");

        $articlesMap = array();
        $articlesKeyMap = array();

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
                $message = "Duplicate Article Found. Article
                $article has been already selected for vehicle
                $registrationNumber. Check your article";
                throw new MaterialReservationException($message);
            }

            if ($articleClass == RequisitionItemTypes::STOCK_ITEM) {
                $articleKey = str_replace("-",
                    "",
                    str_replace(" ", "",
                        $article)
                );
                if (in_array($articleKey, array_keys($articlesKeyMap))) {
                    $message = ErrorMessages::getMessage('err_0038');
                    throw new MaterialReservationException(str_replace(
                            Articles::ARTICLE_FIELD,
                            $article,
                            $message)
                    );
                }

                $articlesKeyMap[$articleKey] = $articleKey;
            }

            $articlesMap[$key] = $registrationNumber;

            $this->vehicleDetailsService->verifyVehicleIsActive($registrationNumber);

            $query = DB::table("$articlesTable");

            $finalQuery = $this->materialValidationService->buildArticleTypeCheckingQuery(
                $query,
                $articleClass,
                $articlesTable
            );

            // move to caller
            $this->materialValidationService->checkArticleType(
                $finalQuery,
                $article,
                $articleClass,
                $registrationNumber,
                ValidationProcess::OTHER
            );
        }

        DB::beginTransaction();

        // generate tms ref
        $requisition_reference_number = DocumentNumberGenerationService::generateReferenceNumber(
            WorkflowModules::WORKSHOP_REQUISITION
        );

        $form_order_number = $this->getGenerateFormOrderNumber($articleClass);

        Log::info("Reservation Ref. " . $requisition_reference_number);
        Log::info("Form Order. " . $form_order_number);
        Log::info("Reservation Item Type " . $materialReservationRequest->get("itemType"));
        Log::info("Determined Reservation Item Type Code " . $articleClass);

        $short_description = "Workshop Reservation for Vehicles Reference $requisition_reference_number";
        $long_description = "Workshop Reservation Reference No. $requisition_reference_number For Vehicles";

        $this->workflowService->initiateWorkflowProcess(
            $requisition_reference_number,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $materialReservationRequest->get('remarks'),
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
                "item_type" => $articleClass,
                "requested_by" => $user->staff_no,
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

        Log::info("Raising Reservation Reference # " . $requisition_reference_number . " successful");

        return response()->json(
            FleetMasterJsonResponse::response(
                'success',
                true,
                str_replace(
                    "@ref",
                    $requisition_reference_number,
                    SystemMessages::REQUISITION_RAISED
                ),
                [],
                URL::signedRoute("list.workshop.requisition")
            )
        );
    }

    /**
     * @param WorkshopServiceRequisitionRequest $requisitionPostRequest
     * @return JsonResponse
     * @throws DuplicateArticleException
     * @throws InvalidArticleTypeException
     * @throws MaterialReservationException
     * @throws VehicleStateException
     * @throws WorkflowTaskCreationFailedException
     */
    public function processJobCardServiceRequest(
        WorkshopServiceRequisitionRequest $requisitionPostRequest
    ): JsonResponse
    {
        Log::info("Creating Workshop Service Request");

        $validFrom = Carbon::now();
        $validTo = Carbon::now();
        $registrationNumber = $requisitionPostRequest->vehicle_registration;

        /********************************************** Save Data **************************************/
        $user = Auth()->user();

        $this->vehicleDetailsService->verifyVehicleIsActive($registrationNumber);

        // check that each article selected is of correct class
        $requestItemType = $requisitionPostRequest->itemType;
        if ($requestItemType == RequisitionItemTypes::SERVICE_ITEM_CODE) {
            throw new InvalidArticleTypeException(ErrorMessages::getMessage('err_0036'));
        }

        $articleClassCode = RequisitionItemTypes::SERVICE;
        $workflowProcess = WorkflowProcessCodes::PurchaseProcess->value;

        $this->materialValidationService->validateServiceArticle(
            $requisitionPostRequest,
            $articleClassCode,
            $registrationNumber
        );

        $formOrder = DocumentNumberGenerationService::generateReferenceNumber(
            WorkflowModules::STOCK_REQUISITION
        );

        $purchaseProcessReference = DocumentNumberGenerationService::generateReferenceNumber(
            WorkflowModules::PURCHASE_REQUISITION
        );

        Log::info("Requisition Ref. " . $purchaseProcessReference);
        Log::info("Doc No. " . $formOrder);
        Log::info("Requisition Item Type " . $requisitionPostRequest->get("itemType"));
        Log::info("Determined Requisition Item Type Code " . $articleClassCode);

        $shortDescription = "Workshop Requisition for Vehicle Reg No. " . $registrationNumber;
        $longDescription = "Workshop Requisition Ref.No. "
            . $purchaseProcessReference
            . " For Vehicle Reg No. " . $registrationNumber;

        DB::beginTransaction();
        $justification = $requisitionPostRequest->remarks;

        $this->workflowService->initiateWorkflowProcess(
            $purchaseProcessReference,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $justification,
            $requisitionPostRequest->total_amount ?? 0,
            $shortDescription,
            $longDescription
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
                "req_no" => $purchaseProcessReference,
                "form_order" => $formOrder,
                "workshop_no" => $workshopCode,
                "item_type" => $articleClassCode,
                "requested_by" => $user->staff_no,
                "veh_reg_no" => $registrationNumber,
                "purchase_office" => $requisitionPostRequest->get("purchase_office"),
                "store" => $storeCode,
                "supplier_code" => $requisitionPostRequest->supplier,
                "valid_date_from" => $validFrom,
                "valid_date_to" => $validTo,
                "comments" => $justification,
                "cost_assigned_to" => "CostCenter",
                "is_fuel" => "N",
                'document_no' => $joCardNumber
            ]
        );

        WorkShopMaterialHeader::create(
            [
                "form_order" => $formOrder,
                "job_card_no" => $joCardNumber,
                "item_type_code" => $articleClassCode,
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
                "req_no" => $purchaseProcessReference,
                "specifications" => $item["service_technical_specification"],
                "description" => $item["service_technical_specification"],
                "reg_no" => $item["vehicle_registration"],
            ]);

            // Verified Og
            WorkShopServiceModel::create([
                "wshp_act_code" => $workshopReference,
                "wshp_code" => $workshopCode,
                "evaluation" => "Y",
                "movt_no" => $formOrder,
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
        Log::info("Requisition " . $purchaseProcessReference . " raised successfully");

        return response()->json(
            FleetMasterJsonResponse::response(
                '',
                true,
                "Requisition "
                . $purchaseProcessReference
                . " Generated and submitted to the next authority for Authorisation",
                [],
                URL::signedRoute("list.workshop.requisition")
            )
        );
    }

    /**
     * @throws VehicleStateException
     * @throws WorkflowTaskCreationFailedException
     * @throws MaterialReservationException
     */
    public function processServiceReservation(
        WorkshopServiceReservationRequest $serviceReservationRequest
    ): JsonResponse
    {
        Log::info("Creating Workshop Service Booking");

        $periodFrom = Carbon::now();
        $periodTo = Carbon::now();
        /********************************************** Save Data **************************************/
        $user = Auth()->user();

        // check that each article selected is of correct class
        $workflowProcess = "";

        // check each article to make sure it's of the correct type and is no active on a reservation for the same car
        $serviceArticlesMap = array();
        foreach ($serviceReservationRequest->get("items") as $item) {
            $article = $item["service_article"];
            $registrationNumber = $item['vehicle_registration'];

            $this->vehicleDetailsService->verifyVehicleIsActive($registrationNumber);

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

            list($articleClass, $workflowProcess) = $this->getArticleClass($itemTypeCode);

            $this->materialValidationService->validateSelectedServiceArticles(
                $articleClass,
                $item["service_article"],
                $registrationNumber
            );

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
        Log::info("Determined Reservation Item Type Code $articleClass");

        $shortDescription = "Workshop Reservation Ref.No. $purchaseProcessReference";
        $longDescription = "Workshop Reservation Ref.No. $purchaseProcessReference";

        $this->workflowService->initiateWorkflowProcess(
            $purchaseProcessReference,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $serviceReservationRequest->remarks,
            $serviceReservationRequest->total_amount ?? 0,
            $shortDescription,
            $longDescription
        );


        $workshopCode = $serviceReservationRequest->get("workshop_code");
        $storeCode = $serviceReservationRequest->get('store_code');

        $matHeader = MaterialHeader::create(
            [
                "created_by" => $user->id,
                "date_created" => Carbon::now(),
                "status" => StatusHelper::new(),
                "req_no" => $purchaseProcessReference,
                "form_order" => $formOrder,
                "workshop_no" => $workshopCode,
                "item_type" => $articleClass,
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

        MaterialReservationMade::dispatch($matHeader, 'service');
        Log::info("Reservation " . $purchaseProcessReference . " raised successfully");

        return response()->json([
            "success" => true,
            "message" => "Reservation $purchaseProcessReference Generated and
            submitted to the next authority for Authorisation",
            "redirectUrl" => URL::signedRoute("list.workshop.requisition"),
        ]);
    }

    /**
     * @throws MaterialReservationException
     */
    public function createWorkshopMaterialStoresReservation(mixed $req_no): mixed
    {
        $requisitionDetail = $this->materialDetailService->getReservationDetail($req_no);

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
            throw new MaterialReservationException(ErrorMessages::getMessage('err_0022'));
        }

        if (!str_starts_with($results, "J02")) {
            throw new MaterialReservationException($results);
        }


        Log::info("Stores Reservation Generated with document " . $results);

        self::updateStPur($requisitionDetail->req_no, $results);

        return $results;
    }

    /**
     * @throws ServiceRequisitionException
     */
    public function createWorkshopNonStockPurchaseProcess($workshopReference): mixed
    {
        $requisitionDetail = $this->materialDetailService->getReservationDetail($workshopReference);

        $materialHeader = WorkShopMaterialHeader::where("form_order", "=", $requisitionDetail->form_order)
            ->where("item_type_code", "=", RequisitionItemTypes::NON_STOCK_ITEM_CODE)
            ->first();
        if (!empty($materialHeader)) {
            $results = $this->procurementService->createPurchaseProcess(
                $workshopReference,
                $requisitionDetail->veh_reg_no,
                $requisitionDetail->form_order,
                $requisitionDetail->purchase_office,
                $materialHeader->job_card_no,
                $requisitionDetail->workshop_no
            );
        } else {
            $results = $this->procurementService->createPurchaseProcessBooking(
                $workshopReference,
                $requisitionDetail->form_order
            );
        }

        if (empty($results)) {
            throw new ServiceRequisitionException("Purchase Process Could Not Be Started ");
        }

        if (!str_starts_with($results, "N01")) {
            throw new ServiceRequisitionException($results);
        }

        self::updateStPur($requisitionDetail->req_no, $results);

        Log::info("Purchase Process Document document " . $results);

        return $results;
    }

    /**
     * @throws FuelRequisitionException
     * @throws ServiceRequisitionException
     */
    public function createWorkshopServicePurchaseProcess(mixed $workshopReference): mixed
    {
        $requisitionDetail = $this->materialDetailService->getReservationDetail($workshopReference);

        $materialHeader = WorkShopMaterialHeader::where(
            "form_order",
            QueryComparisonOperator::EQUALS,
            $requisitionDetail->form_order
        )
            ->where("item_type_code",
                QueryComparisonOperator::EQUALS,
                RequisitionItemTypes::SERVICE_ITEM_CODE)
            ->first();

        if (!empty($materialHeader)) {
            $results = $this->procurementService->createPurchaseProcess(
                $workshopReference,
                $requisitionDetail->veh_reg_no,
                $requisitionDetail->form_order,
                $requisitionDetail->purchase_office,
                $materialHeader->job_card_no,
                $requisitionDetail->workshop_no
            );
        } else {
            $results = $this->procurementService->createPurchaseProcessBooking(
                $workshopReference,
                $requisitionDetail->form_order
            );
        }

        if (empty($results)) {
            throw new ServiceRequisitionException("Purchase Process Could Not Be Started ");
        }

        if (!str_starts_with($results, "N01")) {
            throw new FuelRequisitionException($results);
        }

        self::updateStPur($requisitionDetail->req_no, $results);

        Log::info("Purchase Process Document " . $results);

        return $results;
    }

    public function getWorkShopReservationDetails(mixed $requisitionNumber): array
    {
        $articles = config("tables.table_names.articles");

        // "GEN_MATERIAL_HEADERS.*",
        $header = DB::table("GEN_MATERIAL_HEADERS")
            ->where("GEN_MATERIAL_HEADERS.req_no", $requisitionNumber)
            ->leftJoin("CONFIG_STATUSES",
                "GEN_MATERIAL_HEADERS.status",
                "=", "CONFIG_STATUSES.code")
            ->where("CONFIG_STATUSES.MODULE", "=", "MAT")
            ->select("GEN_MATERIAL_HEADERS.*",
                "CONFIG_STATUSES.name as status_name", "CONFIG_STATUSES.color_code")
            ->get();

        $detail = DB::table("GEN_MATERIAL_HEADERS")
            ->join("GEN_MATERIAL_DETAILS",
                "GEN_MATERIAL_HEADERS.req_no",
                "=",
                "GEN_MATERIAL_DETAILS.req_no")
            ->leftJoin("$articles", "GEN_MATERIAL_DETAILS.MATERIAL_CODE",
                "=", "$articles.CODE_ARTICLE")
            ->where("GEN_MATERIAL_DETAILS.req_no", $requisitionNumber)
            ->select("GEN_MATERIAL_DETAILS.*",
                "$articles.description"
            )
            ->get();

        return [$header->first(), $detail];

    }

    /**
     * @param mixed $jobCardNumber
     * @return Collection
     */
    public function getWorkShopRequisitionItems(mixed $jobCardNumber): Collection
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
            ->leftJoin("$articles",
                "$articles.CODE_ARTICLE",
                "=",
                "services.mat_code")
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
        Log::debug('Loading Materials For Workshop Code ' . $workShopActCode);
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
            ->whereNull(
                "head.deleted_at"
            )
            ->select(
                "det.*",
                "head.status",
                'head.code'
            )->get();
    }

    /**
     * @param Request $request
     * @return string
     * @throws FuelRequisitionException
     * @throws MaterialReservationException
     * @throws ServiceRequisitionException
     * @throws WorkflowTaskCreationFailedException
     */
    public function processWorkshopRequisitionWorkflow(Request $request): string
    {
        $reference = $request->get('reference');
        $userAction = strtolower(trim($request->get('Approved')));

        $requisitionDetail = $this->materialDetailService->getReservationDetail($reference);

        if ($requisitionDetail->item_type == RequisitionItemTypes::SERVICE
            || $requisitionDetail->item_type == RequisitionItemTypes::NON_STOCK_ITEM) {
            $workflowProcessCode = WorkflowProcessCodes::PurchaseProcess->value;
        } else {
            $workflowProcessCode = WorkflowProcessCodes::StoresRequisition->value;
        }

        $actionTaken = '';
        $message = '';
        $action = 0;

        if ($userAction == WorkflowActions::APPROVE) {
            $action = WorkflowActions::approve();
            $actionTaken = "Approved";
            $message = 'Request Approved Successfully.';
        } elseif ($userAction == WorkflowActions::REJECT) {
            $action = WorkflowActions::reject();
            $actionTaken = "Rejected";
            $message = 'Request Rejected.';
        } elseif ($userAction == WorkflowActions::SEND_BACK) {
            $action = WorkflowActions::sendBack();
            $actionTaken = "Send Back";
            $message = 'Request Sent Back To Originator.';
        }

        DB::beginTransaction();
        list($nextStepId, $nextUser) = $this->workflowService->invokeWorkFlow(
            $reference,
            $workflowProcessCode,
            $action,
            $actionTaken,
            $request->get('Comments')
        );

        $status = '';
        if ($nextStepId == 100) {
            list($message, $status) = $this->requisitionApproved(
                $action,
                $requisitionDetail,
                $request,
                $message,
                $status);
        } else {

            if ($action = WorkflowActions::approve()) {
                $message = self::APPROVED . $nextUser;
                $status = StatusHelper::partiallyAuthorised();
            } elseif ($action == WorkflowActions::sendBack()) {
                $status = StatusHelper::sentBack();
                $message = 'Request Returned to Originator';
            }
        }
        $this->updateStatus($reference, $status);

        DB::commit();

        return $message;
    }

    /**
     * @param mixed $registrationNumber
     * @param string $workflowProcess
     * @param WorkshopRequisitionRequest $requisitionPostRequest
     * @param  $user
     * @param string $item_type
     * @param  $validFrom
     * @param  $dateExpected
     * @return array
     * @throws WorkflowTaskCreationFailedException
     */
    public function saveJobCardMaterialRequest(
        mixed                      $registrationNumber,
        string                     $workflowProcess,
        WorkshopRequisitionRequest $requisitionPostRequest,
                                   $user,
        string                     $item_type,
                                   $validFrom,
                                   $dateExpected): array
    {
        DB::beginTransaction();

        $requisition_reference_number = DocumentNumberGenerationService::generateReferenceNumber(
            WorkflowModules::WORKSHOP_REQUISITION
        );

        $formOrderNumber = $this->getGenerateFormOrderNumber($item_type);

        $longDescription = "Workshop Requisition Ref.No. " .
            $requisition_reference_number
            . " For Vehicle Reg No. "
            . $registrationNumber;
        $shortDescription = "Workshop Requisition for Vehicle Reg No. " . $registrationNumber;

        $this->workflowService->initiateWorkflowProcess(
            $requisition_reference_number,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $requisitionPostRequest->remarks,
            $requisitionPostRequest->total_amount ?? 0,
            $shortDescription,
            $longDescription
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
                "form_order" => $formOrderNumber,
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
                "form_order" => $formOrderNumber,
                "job_card_no" => $jobCardNumber,
                "item_type_code" => $item_type,
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

            if ($item_type == RequisitionItemTypes::STOCK_ITEM) {
                WorkShopMaterial::create([
                    "wshp_act_code" => $workshopReference,
                    "workshop_code" => $workshopCode,
                    'sch_flouted' => 'N',
                    "form_order" => $formOrderNumber,
                    "evaluation" => "Y",
                    "date_mat" => Carbon::now(),
                    "mat_code" => $item["articleCode"],
                    "unit_of_measure" => $item["unit_of_measure"],
                    "quantity" => $item["quantity"],
                    "amount" => (float)$item["quantity"] * (float)$item["unit_price"],
                    "price" => $item["unit_price"],
                    "store_code" => $storeCode,
                    "supplier_code" => $requisitionPostRequest->get('supplier'),
                    "veh_reg_no" => $item["registration"],
                    "specifications" => $item["technical_specification"],
                    "requested_by" => $user->staff_no,
                    "requested_by_id" => $user->id,
                    "status" => StatusHelper::new(),
                    "created_by" => $user->staff_no
                ]);
            } else {
                WorkShopServiceModel::create([
                    "wshp_act_code" => $workshopReference,
                    "wshp_code" => $workshopCode,
                    "evaluation" => "Y",
                    "movt_no" => $formOrderNumber,
                    "date_send" => Carbon::now(),
                    "mat_code" => $item["articleCode"],
                    "unit_of_measure" => $item["unit_of_measure"],
                    "quantity" => $item["quantity"],
                    "amount_est" => (float)$item["quantity"] * (float)$item["unit_price"],
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

        return array($requisition_reference_number, $matHeader);
    }

    /**
     * @param mixed $articleClassCode
     * @return string
     */
    public function getGenerateFormOrderNumber(mixed $articleClassCode): string
    {
        $moduleCode = '';
        if ($articleClassCode == RequisitionItemTypes::STOCK_ITEM) {
            $moduleCode = WorkflowModules::STOCK_REQUISITION;
        } elseif ($articleClassCode == RequisitionItemTypes::NON_STOCK_ITEM) {
            $moduleCode = WorkflowModules::PURCHASE_REQUISITION;
        }

        return DocumentNumberGenerationService::generateReferenceNumber($moduleCode);
    }

    /**
     * @param mixed $requestItemType
     * @return array (Article Class Code, Workflow Process Code)
     */
    public function getArticleClass(mixed $requestItemType): array
    {
        $articleClass = '';
        $workflowProcess = '';
        if ($requestItemType == RequisitionItemTypes::STOCK_ITEM_CODE) {
            $articleClass = RequisitionItemTypes::STOCK_ITEM;
            $workflowProcess = WorkflowProcessCodes::StoresRequisition->value;
        } elseif ($requestItemType == RequisitionItemTypes::NON_STOCK_ITEM_CODE) {
            $articleClass = RequisitionItemTypes::NON_STOCK_ITEM;
            $workflowProcess = WorkflowProcessCodes::PurchaseProcess->value;
        } elseif ($requestItemType == RequisitionItemTypes::SERVICE_ITEM_CODE) {
            $articleClass = RequisitionItemTypes::SERVICE;
            $workflowProcess = WorkflowProcessCodes::PurchaseProcess->value;
        }

        return array($articleClass, $workflowProcess);
    }

    /**
     * @param int $action
     * @param mixed $requisitionDetail
     * @param Request $request
     * @param string $message
     * @param string $status
     * @return array
     * @throws FuelRequisitionException
     * @throws MaterialReservationException
     * @throws ServiceRequisitionException
     */
    public function requisitionApproved(int     $action,
                                        mixed   $requisitionDetail,
                                        Request $request,
                                        string  $message,
                                        string  $status): array
    {
        if ($action == WorkflowActions::approve()) {
            switch ($requisitionDetail->item_type) {
                case RequisitionItemTypes::SERVICE:
                    $purchaseProcessNumber = $this->createWorkshopServicePurchaseProcess(
                        $request->get('reference')
                    );
                    $message = $message
                        . ' Purchase Process No.: ' . $purchaseProcessNumber;
                    break;
                case RequisitionItemTypes::NON_STOCK_ITEM:
                    $purchaseProcessNumber = $this
                        ->createWorkshopNonStockPurchaseProcess($request->get('reference'));
                    $message = $message . ' Purchase Process No.: ' . $purchaseProcessNumber;
                    break;
                case RequisitionItemTypes::STOCK_ITEM:
                    $reservationNumber = $this->createWorkshopMaterialStoresReservation(
                        $request->get('reference')
                    );
                    $message = $message . ' Stores Reservation No.: ' . $reservationNumber;
                    break;
                default:
                    throw new MaterialReservationException("ITEM TYPE NOT");
            }
            $status = StatusHelper::authorised();
        } elseif ($action == WorkflowActions::reject()) {
            $status = StatusHelper::rejected();
            $message = 'Request Rejected';
        }
        return array($message, $status);
    }

}
