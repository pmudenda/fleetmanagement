<?php

namespace App\Services\WorkShopManagement;

use App\Constants\QueryComparisonOperator;
use App\Constants\SystemMessages;
use App\Enums\ConfigurationTypes;
use App\Enums\Modules;
use App\Enums\RequisitionItemTypes;
use App\Enums\WorkflowProcessCodes;
use App\Events\WorkOrderCompleted;
use App\Exceptions\DuplicateDefectException;
use App\Exceptions\OrganisationUnitStateException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Requests\WorkShopManagement\JobCardRequest;
use App\Http\Requests\WorkShopManagement\JobCardTaskAssignment;
use App\Http\Requests\WorkShopManagement\JobCardTaskReassignment;
use App\Http\Requests\WorkShopManagement\VehicleDefects;
use App\Http\Requests\WorkShopManagement\WorkOrderClosure;
use App\Models\Common\File;
use App\Models\MaterialHeader;
use App\Models\Settings\Accessory;
use App\Models\Settings\GeneralTable;
use App\Models\VehicleManagement\VehicleHeader;
use App\Models\WorkShopManagement\AssessmentObservation;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Models\WorkShopManagement\WorkShopComment;
use App\Models\WorkShopManagement\WorkshopLabour;
use App\Models\WorkShopManagement\WorkShopTable;
use App\Models\WorkShopManagement\WorkShopVehicleAccessory;
use App\Models\WorkShopManagement\WorkShopVehicleDefect;
use App\Services\FileUploads\FileUploadService;
use App\Services\Integration\ProcurementSystemIntegrationService;
use App\Services\Logging\HistoryService;
use App\Services\Requisitions\VehicleAssignmentValidationService;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\Workflow\WorkflowService;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class WorkshopService
{

    private WorkflowService $workflowService;
    protected ProcurementSystemIntegrationService $procurementService;
    private JobCardValidationService $jobCardValidationService;
    private VehicleAssignmentValidationService $vehicleAssignmentStateValidateService;

    public function __construct(
        WorkflowService                     $workflowService,
        ProcurementSystemIntegrationService $procurementService,
        JobCardValidationService            $jobCardValidationService,
        VehicleAssignmentValidationService  $vehicleAssignmentStateValidateService
    )
    {
        $this->procurementService = $procurementService;
        $this->workflowService = $workflowService;
        $this->jobCardValidationService = $jobCardValidationService;
        $this->vehicleAssignmentStateValidateService = $vehicleAssignmentStateValidateService;
    }

    /**
     * @throws OrganisationUnitStateException
     */
    public function createJobCard(JobCardRequest $request)
    {
        $user = auth()->user();

        // perform update
        $vehicleRegistration = $request->get("vehicle_registration");

        $this->vehicleAssignmentStateValidateService
            ->checkVehicleAssignedUserUnitAndBuCcStatus($vehicleRegistration);

        if ($request->has("job_card_number") && !empty($request->get("job_card_number"))) {
            // update the information
            $details = JobCardHeader::where("job_card_no", "=", $request->get("job_card_number"))->orderBy("id", "desc")
                ->first();

            $details->reg_no = $vehicleRegistration;
            $details->workshop_code = $request->get("workshop");
            $details->repair_type = $request->get("repairType");
            $details->accident_ref = $request->get("accident_number") ?? "N/A";
            $details->millage_in = $request->get("current_odometer");
            $details->fuel_level_in = $request->get("fuel_level");
            $details->sub_fuel_level_in = $request->get("sub_fuel_level");
            $details->driver_in = $request->get("driver_staff_number");
            $details->modified_by = $user->id;
            $details->save();

            return $details;
        }

        $this->jobCardValidationService->validate($request);

        $workshop_number = DocumentNumberGenerationService::generateReferenceNumber(
            Modules::WORKSHOP_DOCUMENT->value);
        $doc_number = DocumentNumberGenerationService::generateReferenceNumber(
            Modules::JOB_CARD->value);

        $section = GeneralTable::where("name", "=", "RECEPTION")
            ->where("type", ConfigurationTypes::WORK_SHOP_SECTION)
            ->first();

        if (empty($section)) {
            Log::info("Receiving Section Not Found");
        }

        $data = [
            "reg_no" => $vehicleRegistration,
            "job_card_no" => $doc_number,
            "wshp_act_code" => $workshop_number,
            "date_in" => Carbon::now(),
            "workshop_code" => $request->get("workshop"),
            "time_in" => Carbon::now(),
            "repair_type" => $request->get("repairType"),
            "received_by" => $user->staff_no,
            "receiving_section" => $section->code,
            "accident_ref" => $request->get("accident_number"),
            "millage_in" => $request->get("current_odometer"),
            "fuel_level_in" => $request->get("fuel_level"),
            "driver_in" => $request->get("driver_staff_number"),
            "created_by" => $user->id,
            'status' => StatusHelper::new(),
            'step' => 1
        ];

        DB::beginTransaction();

        $jobCardHeader = JobCardHeader::create($data);

        $this->moveVehicleToWorkShop($vehicleRegistration);

        DB::commit();
        return $jobCardHeader;
    }

    public function getJobCardDetails(mixed $reference)
    {
        $query = DB::table("WM_JOB_CARD_HEADER")
            ->leftJoin("SEC_USERS", "WM_JOB_CARD_HEADER.received_by",
                "=", "SEC_USERS.staff_no")
            ->join("CONFIG_GENERAL_TABLES", function (JoinClause $joinClause) {
                $joinClause->on("WM_JOB_CARD_HEADER.receiving_section",
                    "=",
                    "CONFIG_GENERAL_TABLES.code")
                    ->where("CONFIG_GENERAL_TABLES.type",
                        "=",
                        ConfigurationTypes::WORK_SHOP_SECTION);
            })
            ->leftJoin("CONFIG_STATUSES",
                "WM_JOB_CARD_HEADER.status",
                "=",
                "CONFIG_STATUSES.code")
            ->where("WM_JOB_CARD_HEADER.job_card_no", "=", $reference)
            ->select("WM_JOB_CARD_HEADER.*",
                "CONFIG_GENERAL_TABLES.name as section_in_name",
                "SEC_USERS.name as service_advisor",
                "CONFIG_STATUSES.name as status_name",
                "CONFIG_STATUSES.color_code",
            )
            ->get();

        return $query->first();
    }

    public function processJobCardAVehicleAssessment(Request $request): void
    {
        DB::beginTransaction();

        $jobCardVoucher = $request->get("job_card_voucher");
        $referenceNumber = $request->get("workshop_reference");
        $comment = $request->get("accessoriesRemarks");
        $accessoryNames = Accessory::where(
            "status",
            "=",
            StatusHelper::active()
        )->get();

        $user = auth()->user();
        Log::info("Saving Accessories on " . $jobCardVoucher);

        foreach ($accessoryNames as $accessoryName) {
            $accessoryCode = $accessoryName->code;
            $response = $request->get("field_" . trim($accessoryCode));
            $remarks = $request->get("comment_" . trim($accessoryCode));

            WorkShopVehicleAccessory::updateOrCreate(
                [
                    "job_card_no" => trim($jobCardVoucher),
                    "workshop_reference" => trim($referenceNumber),
                    "code" => trim($accessoryCode),
                ],
                [
                    "name" => $accessoryName->name,
                    "remarks" => $remarks,
                    "is_present" => $response
                ]
            );
        }

        $uploadedFiles = $this->uploadAttachments($request, $referenceNumber, $user);

        $this->saveJobCardAssessmentObservation(
            $request,
            $user->staff_no,
            $uploadedFiles
        );

        Log::info("General Comments  " . $request->get('accessoriesRemarks'));
        if (!empty($comment)) {
            WorkShopComment::firstOrCreate(
                [
                    "workshop_reference" => $request->get('workshop_reference'),
                    "type" => "ACC",
                ],
                [
                    "remarks" => $request->get('accessoriesRemarks'),
                    "status" => StatusHelper::new(),
                    "created_by" => $user->staff_no
                ]);
        }

        DB::commit();
    }

    /**
     * @param Request $request
     * @param string $referenceNumber
     * @param $user
     * @return File[]|array
     */
    private function uploadAttachments(Request $request, string $referenceNumber, $user): array
    {
        $attachedFiles = $request->get('attachment');
        $uploadedFiles = [];

        if (!empty($attachedFiles)) {
            Log::debug("Upload Images for $referenceNumber");
            $uploadedFiles = FileUploadService::uploadFile(
                $request,
                "attachment",
                "Assessment",
                $referenceNumber,
                "Observations",
                "Observations",
                $user
            );
        }

        Log::debug(sizeof($uploadedFiles) . " Images Uploaded");
        return $uploadedFiles;
    }

    /**
     * @param Request $request
     * @param string $staffNumber
     * @param array $uploadedFiles
     * @return void
     */
    private function saveJobCardAssessmentObservation(Request $request,
                                                      string  $staffNumber,
                                                      array   $uploadedFiles): void
    {

        $toSave = [];
        if ($request->has('observation')) {
            $key = 0;
            foreach ($request->get('observation') as $observation) {
                $toSave[] = array(
                    'observation' => $observation,
                    'file' => ($key <= sizeof($uploadedFiles) - 1) ? $uploadedFiles[$key]->path : null
                );
                $key++;
            }
        } elseif (!empty($uploadedFiles)) {
            Log::info("Observation Images Found");
            foreach ($uploadedFiles as $uploadedFile) {
                $toSave[] = array('observation' => null, 'file' => $uploadedFile->path);
            }
        }

        if (empty($toSave)) {
            return;
        }

        foreach ($toSave as $item) {
            Log::info("Looping through Items to save");
            if (!empty($item['file']) && !empty($item['observation'])) {
                Log::info($item['file'] . " - " . $item['observation']);
                AssessmentObservation::create([
                    'reference' => $request->get('workshop_reference'),
                    'image_path' => $item['file'],
                    'remarks' => $item['observation'],
                    'reported_by' => $staffNumber
                ]);
            }
        }
        Log::info("Observation Uploaded");
    }

    /**
     * @throws DuplicateDefectException
     */
    public function createJobCardDefects(VehicleDefects $request): void
    {
        DB::beginTransaction();
        $vehicleRegistrationNumber = $request->get('vehicle_registration');
        $workShopReference = $request->get("workshop_reference");
        foreach ($request->get("items") as $defect) {

            $sameDefect = WorkShopVehicleDefect::where("workshop_reference", "=", $workShopReference)
                ->where("veh_sys", "=", $defect["vehicleSystem"])
                ->where("defect_category_code", "=", $defect["defectCategory"])
                ->where("defect_code", "=", $defect["defect"])
                ->first();

            if (!empty($sameDefect)) {
                throw new DuplicateDefectException(
                    "Defect already Registered for vehicle $vehicleRegistrationNumber");
            }

            $dbDefect = WorkShopTable::where('parent', '=', $defect["defectCategory"])
                ->where('code', '=', $defect["defect"])
                ->first();
            WorkShopVehicleDefect::firstOrCreate(
                [
                    "workshop_reference" => $workShopReference,
                    "veh_sys" => $defect["vehicleSystem"],
                    "defect_category_code" => $defect["defectCategory"],
                    "defect_code" => $defect["defect"],
                ],
                [
                    "defect_id" => $dbDefect->id,
                    "defect_name" => $dbDefect->description,
                    "section_code" => $defect["workshopSection"],
                    "created_by" => auth()->user()->staff_no,
                    "date_def" => Carbon::parse($defect["date_def"])
                ]);
        }

        if (!empty($request->remarks)) {
            WorkShopComment::firstOrCreate(
                [
                    "workshop_reference" => $request->workshop_reference,
                    "type" => "DEF",
                ],
                [
                    "remarks" => $request->remarks ?? " ",
                    "status" => StatusHelper::new(),
                    "created_by" => auth()->user()->staff_no
                ]);
        }

        DB::commit();
    }

    public function getJobCardHeader(mixed $status): Collection
    {
        return DB::table("WM_JOB_CARD_HEADER header")
            ->leftJoin("SEC_USERS",
                "header.received_by",
                "=",
                "SEC_USERS.staff_no")
            ->leftJoin("CONFIG_GENERAL_TABLES", function (JoinClause $joinClause) {
                $joinClause->on("header.receiving_section", "=", "CONFIG_GENERAL_TABLES.code")
                    ->where("CONFIG_GENERAL_TABLES.type",
                        "=",
                        ConfigurationTypes::WORK_SHOP_SECTION);
            })
            ->leftJoin("CONFIG_GENERAL_TABLES as config", function (JoinClause $clause) {
                $clause->on("header.repair_type", "=", "config.code")
                    ->where("config.type", "=", ConfigurationTypes::REPAIR_TYPE);
            })
            ->leftJoin("CONFIG_WORKSHOP", "header.receiving_section", "=", "CONFIG_WORKSHOP.workshop_code")
            ->where("header.status", '=', $status)
            ->select("header.*",
                "CONFIG_WORKSHOP.workshop_name",
                "config.name as repair_type_name",
                "CONFIG_GENERAL_TABLES.name as section_in_name",
                "SEC_USERS.name as service_advisor")
            ->orderBy('header.created_at', 'desc')
            ->get();

    }

    public function getWorkShopPurchaseOfficeAndStore($workshopCode)
    {
        $stores = config("tables.table_names.stores");
        $purchaseOffices = config("tables.table_names.purchaseOffices");

        $data = DB::table("config_workshop")
            ->leftJoin("$stores", "config_workshop.store_code", "=", "$stores.code_store")
            ->leftJoin("$purchaseOffices", "config_workshop.area_code", "=", "$purchaseOffices.area")
            ->where("config_workshop.workshop_code", "=", $workshopCode)
            ->select("config_workshop.*",
                "$stores.code_store as store_code",
                "$stores.description as store_name",
                "$purchaseOffices.description as purchase_office",
                "$purchaseOffices.area as purchase_office_area",
                "$purchaseOffices.code_office as purchase_office_code")
            ->get();
        return $data->first();
    }

    private function moveVehicleToWorkShop($vehicleRegistration): void
    {
        $rowsAffected = VehicleHeader::where("registration_number", $vehicleRegistration)
            ->update([
                "status" => StatusHelper::vehicleInWorkshop(),
                'updated_at' => Carbon::now()
            ]);

        Log::info('Setting Vehicle State To In Workshop ' . $rowsAffected);
    }

    /**
     * @throws WorkflowTaskCreationFailedException
     */
    public function closeJobCard(WorkOrderClosure $request): JsonResponse
    {
        $user = Auth::user();

        DB::beginTransaction();

        $workOrderNumber = $request->get("job_card_number");
        $jobCardHeader = JobCardHeader::where("job_card_no", "=", $workOrderNumber)
            ->first();

        $vehicleHeader = VehicleHeader::where('registration_number', '=', $jobCardHeader->reg_no)->first();
        $vehicleHeader->status = StatusHelper::active();
        $vehicleHeader->save();

        $dataBefore = $jobCardHeader->toArray();

        Log::info('closing job card task' . $workOrderNumber);
        $this->workflowService->cancelProcessTask(
            $workOrderNumber,
            WorkflowProcessCodes::WorkOrderOpened->value
        );

        $workOrderNumber = $jobCardHeader->job_card_no;

        $jobCardHeader->status = StatusHelper::pendingApproval();
        $jobCardHeader->date_out = Carbon::now();
        $jobCardHeader->time_out = Carbon::now();
        $jobCardHeader->dispatched_by = $user->staff_no;
        $jobCardHeader->sub_fuel_level_out = '';
        $jobCardHeader->millage_out = $request->get("exitOdometer");
        $jobCardHeader->fuel_level_out = $request->get("fuel_level");
        $jobCardHeader->driver_out = $request->get("driver_out");
        $jobCardHeader->modified_by = $user->staff_no;
        $jobCardHeader->status = StatusHelper::closed();
        $jobCardHeader->updated_at = Carbon::now();
        $totalWorkOrderAmount = $request->get('workOrderTotalAmount');

        foreach ($request->get("items") as $labourItem) {

            WorkshopLabour::firstOrCreate(
                [
                    'wshp_act_code' => $jobCardHeader->wshp_act_code,
                    'wshp_code' => $jobCardHeader->workshop_code,
                    'def_no' => $labourItem['assignedDefect'],
                ],
                [
                    'section' => $labourItem['workshopSection'],
                    'evaluation' => 'N',
                    'date_lab' => Carbon::now(),
                    'mechanic' => $labourItem['mechanic'],
                    'hours_worked' => $labourItem['hoursWorked'],
                    'rate' => (float)$labourItem['ratePerHour'],
                    'total_amount' => (float)$labourItem['totalAmount'],
                    'created_by' => $user->staff_no,
                    'type_of_hour' => $labourItem['shiftType'],
                ]);

            $totalWorkOrderAmount += (float)$labourItem['totalAmount'];
        }

        $jobCardHeader->repair_cost = $totalWorkOrderAmount;

        $jobCardHeader->save();

        HistoryService::update(
            $dataBefore,
            $jobCardHeader->toArray(),
            $jobCardHeader->job_card_no,
            "Job Card Closure",
            "Exit from Workshop");

        $jobCardRequisitions = MaterialHeader::where('veh_reg_no', $jobCardHeader->reg_no)
            ->whereIn('status', [
                StatusHelper::new(),
                StatusHelper::partiallyAuthorised(),
                StatusHelper::authorised(),
                StatusHelper::partiallyReleased(),
                StatusHelper::issued()
            ])
            ->where('document_no', '=', $jobCardHeader->job_card_no)
            ->where('is_fuel', '=', 'N')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($jobCardRequisitions as $requisition) {
            if (in_array($requisition->item_type,
                [
                    RequisitionItemTypes::SERVICE,
                    RequisitionItemTypes::NON_STOCK_ITEM])
            ) {
                if ($requisition->status === StatusHelper::issued()) {
                    MaterialHeader::where("req_no", $requisition->req_no)
                        ->update(["document_no" => null]);
                } elseif (empty($requisition->st_pur)) {
                    $processCode = WorkflowProcessCodes::PurchaseProcess->value;
                    $this->workflowService->cancelProcessTask(
                        $requisition->req_no,
                        $processCode
                    );
                }
            } else {
                $processCode = WorkflowProcessCodes::StoresRequisition->value;
                $this->workflowService->cancelProcessTask(
                    $requisition->req_no,
                    $processCode
                );
                $this->procurementService->cancelStoresRequisition(
                    $requisition->st_pur,
                    SystemMessages::EXIT_FROM_WORKSHOP);
            }
        }

        DB::commit();

        WorkOrderCompleted::dispatch($jobCardHeader, "job_card_closed");

        return response()->json(
            [
                "success" => true,
                "payload" => [],
                "message" => "Job Card $workOrderNumber, Closed Successfully",
                "redirectUrl" => URL::signedRoute("workOrder.list"),
            ]
        );
    }

    public function saveJobCardWorkAssignments(JobCardTaskAssignment $request): JsonResponse
    {
        $user = Auth::user();

        DB::beginTransaction();

        $jobCardNumber = $request->get("jobCardNumber");
        $workOrder = JobCardHeader::where("job_card_no", "=", $jobCardNumber)
            ->first();

        foreach ($request->validated("items") as $labourItem) {
            WorkshopLabour::firstOrCreate(
                [
                    'wshp_act_code' => $workOrder->wshp_act_code,
                    'def_no' => $labourItem['assignedDefect'],
                ],

                [
                    'wshp_code' => $workOrder->workshop_code,
                    'section' => $labourItem['workshopSection'],
                    'evaluation' => 'N',
                    'job_card_instruction' => $labourItem['jobCardInstruction'],
                    'date_lab' => Carbon::now(),
                    'mechanic' => $labourItem['mechanic'],
                    'defect_id' => $labourItem['assignedDefectId'],
                    'created_by' => $user->staff_no,
                ]);
        }

        DB::commit();

        return response()->json(
            [
                "success" => true,
                "payload" => [],
                "message" => "Work Assignments Saved Successfully",
                "redirectUrl" => URL::signedRoute("workOrder.list"),
            ]
        );
    }

    public function saveJobCardWorkReassignments(JobCardTaskReassignment $request): JsonResponse
    {
        $recordId = $request->get("reassignmentReference");
        $assignmentRecord = WorkshopLabour::where("id", "=", $recordId)->first();
        $recordBefore = $assignmentRecord->toArray();
        DB::beginTransaction();
        $assignmentRecord->mechanic = $request->validated('reassignTo');
        $assignmentRecord->section = $request->validated('reassignmentDefectSection');
        $assignmentRecord->section = $request->validated('reassignmentDefectSection');
        $assignmentRecord->updated_at = Carbon::now();

        $assignmentRecord->save();

        HistoryService::update($recordBefore,
            $assignmentRecord->toArray(),
            $assignmentRecord->wshp_act_code,
            'Job Card Task Reassignment',
            $request->validated('reassignmentJustification'));

        DB::commit();

        return response()->json(
            [
                "success" => true,
                "payload" => [],
                "message" => "Work Assignments Saved Successfully",
                "redirectUrl" => URL::signedRoute("workOrder.list"),
            ]
        );
    }

    public function getReservedMaterialsAndServices(string $vehicleRegistration): Collection
    {
        return DB::table("GEN_MATERIAL_HEADERS mat_header")
            ->join("GEN_MATERIAL_DETAILS mat_detail",
                "mat_header.req_no",
                QueryComparisonOperator::EQUALS,
                "mat_detail.req_no")
            ->whereNull("mat_header.document_no")
            ->where("mat_detail.reg_no",
                QueryComparisonOperator::EQUALS,
                $vehicleRegistration)
            ->where("mat_header.is_fuel",
                QueryComparisonOperator::NOT_EQUAL,
                'Y')
            ->whereNull("mat_detail.claimed")
            ->whereIn('mat_header.status',
                [
                    StatusHelper::new(),
                    StatusHelper::authorised(),
                    StatusHelper::partiallyReleased(),
                    StatusHelper::issued()
                ])
            ->select("mat_header.*",
                "mat_detail.*"
            )->get();
    }
}
