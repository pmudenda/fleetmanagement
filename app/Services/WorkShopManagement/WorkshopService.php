<?php

namespace App\Services\WorkShopManagement;

use App\Enums\ConfigurationTypes;
use App\Enums\Modules;
use App\Enums\RequisitionItemTypes;
use App\Events\WorkOrderCompleted;
use App\Exceptions\DuplicateDefectException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Requests\VehicleDefectsRequest;
use App\Http\Requests\WorkShopManagement\JobCardRequest;
use App\Http\Requests\WorkShopManagement\JobCardTaskAssignment;
use App\Http\Requests\WorkShopManagement\JobCardTaskReassignment;
use App\Http\Requests\WorkShopManagement\WorkOrderClosure;
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
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\Workflow\WorkflowService;
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

    public function __construct(
        WorkflowService                     $workflowService,
        ProcurementSystemIntegrationService $procurementService)
    {
        $this->procurementService = $procurementService;
        $this->workflowService = $workflowService;
    }

    public function createJobCard(JobCardRequest $request)
    {
        $user = auth()->user();

        /// $receiverParts = explode("|", $request->get("service_advisor"));
        // perform update
        $vehicleRegistration = $request->get("vehicle_registration");
        if ($request->has("job_card_number") && !empty($request->get("job_card_number"))) {
            // update the information
            $details = JobCardHeader::where("job_card_no", "=", $request->get("job_card_number"))->orderBy("id", "desc")
                ->first();

            $details->reg_no = $vehicleRegistration;
            $details->workshop_code = $request->get("workshop");
            $details->repair_type = $request->get("repairType");

            //$details->date_in = Carbon::parse(trim($request->get("date_of_req")));
            //$details->time_in = Carbon::parse(trim($request->get("timeIn")))->format("H:i:s");
            //$details->received_by = $user->staff_no;
            //$details->receiving_section = $section->code;

            $details->accident_ref = $request->get("accident_number") ?? "N/A";
            $details->millage_in = $request->get("current_odometer");
            $details->fuel_level_in = $request->get("fuel_level");
            $details->sub_fuel_level_in = $request->get("sub_fuel_level");
            $details->driver_in = $request->get("driver_staff_number");
            $details->modified_by = $user->id;
            $details->save();

            return $details;
        }

        $workshop_number = DocumentNumberGenerationService::generateReferenceNumber(Modules::WORKSHOP_DOCUMENT);
        $doc_number = DocumentNumberGenerationService::generateReferenceNumber(Modules::JOB_CARD);

        $section = GeneralTable::where("name", "=", "RECEPTION")
            ->where("type", ConfigurationTypes::WORK_SHOP_SECTION)
            ->first();

        if (empty($section)) {
            Log::info("Receiving Section Not Found");
        }


        $data = [
            // "veh_reg" => $request->get("vehicle_registration"),
            "reg_no" => $vehicleRegistration,
            "job_card_no" => $doc_number,
            // "workshop_doc_no" => $workshop_number,
            "wshp_act_code" => $workshop_number,
            "date_in" => Carbon::now(), //Carbon::createFromFormat("Y-m-d", trim($request->get("date_of_req"))),
            "workshop_code" => $request->get("workshop"),
            "time_in" => Carbon::now(),//(trim($request->get("timeIn")))->format("H:i:s"),
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
            ->leftJoin("SEC_USERS", "WM_JOB_CARD_HEADER.received_by", "=", "SEC_USERS.staff_no")
            ->leftJoin("CONFIG_GENERAL_TABLES", "WM_JOB_CARD_HEADER.receiving_section", "=", "CONFIG_GENERAL_TABLES.code")
            ->leftJoin("CONFIG_STATUSES", "WM_JOB_CARD_HEADER.status", "=", "CONFIG_STATUSES.code")
            ->where("CONFIG_GENERAL_TABLES.type", "=", ConfigurationTypes::WORK_SHOP_SECTION)
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

    public function createJobCardAccessories(Request $request): void
    {
        DB::beginTransaction();
        $job_card_voucher = $request->get("job_card_voucher");
        $reference_number = $request->get("workshop_reference");
        $comment = $request->get("accessoriesRemarks");
        $accessoryNames = Accessory::where("status", "=", StatusHelper::active())
            ->get();
        $user = auth()->user();
        Log::info("Saving Accessories on " . $job_card_voucher);

        foreach ($accessoryNames as $accessoryName) {
            $accessoryCode = $accessoryName->code;
            $response = $request->get("field_" . trim($accessoryCode));
            $remarks = $request->get("comment_" . trim($accessoryCode));

            WorkShopVehicleAccessory::updateOrCreate(
                [
                    "job_card_no" => trim($job_card_voucher),
                    "workshop_reference" => trim($reference_number),
                    "code" => trim($accessoryCode),
                ],
                [
                    "name" => $accessoryName->name,
                    "remarks" => $remarks,
                    "is_present" => $response
                ]
            );
        }

        $attachedFiles = $request->get('attachment');
        // $observations = $request->get('observation');
        $uploadedFiles = [];

        if (!empty($attachedFiles)) {
            $uploadedFiles = FileUploadService::uploadFile(
                $request,
                "attachment",
                "Assessment",
                $reference_number,
                "Observations",
                "Observations",
                $user
            );
        }

        $toSave = [];
        if($request->has('observation')){
            for ($key = 0; $key < sizeof($request->observation); $key++) {
                Log::info(($key <= sizeof($uploadedFiles) - 1) ? $uploadedFiles[$key]->path : "No More Attachments");
                $toSave[] = array('observation' => $request->observation[$key], 'file' => ($key <= sizeof($uploadedFiles) - 1) ? $uploadedFiles[$key]->path : null);
            }
        }

        if (sizeof($toSave) == 0 && !empty($uploadedFiles)) {
            foreach ($uploadedFiles as $uploadedFile) {
                $toSave[] = array('observation' => null, 'file' => $uploadedFile->path);
            }
        }

        Log::info("Number Of Items To Save " . sizeof($toSave));

        if (!empty($toSave)) {
            foreach ($toSave as $item) {
                if (!empty($item['file']) && !empty($item['observation'])) {
                    AssessmentObservation::create([
                        'reference' => $request->get('workshop_reference'),
                        'image_path' => $item['file'],
                        'remarks' => $item['observation'],
                        'reported_by' => $user->staff_no
                    ]);
                }
            }
        }

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
     * @throws DuplicateDefectException
     */
    public function createJobCardDefects(VehicleDefectsRequest $request): void
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
                throw new DuplicateDefectException("Defect already Registered for vehicle $vehicleRegistrationNumber");
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

    public function getJobCardHeader(): Collection
    {
        return DB::table("WM_JOB_CARD_HEADER header")
            ->leftJoin("SEC_USERS", "header.received_by", "=", "SEC_USERS.staff_no")
            ->leftJoin("CONFIG_GENERAL_TABLES", "header.receiving_section", "=", "CONFIG_GENERAL_TABLES.code")
            ->leftJoin("CONFIG_GENERAL_TABLES as config", "header.repair_type", "=", "config.code")
            ->leftJoin("CONFIG_WORKSHOP", "header.receiving_section", "=", "CONFIG_WORKSHOP.workshop_code")
            ->where("CONFIG_GENERAL_TABLES.type", "=", ConfigurationTypes::WORK_SHOP_SECTION)
            ->where("config.type", "=", ConfigurationTypes::REPAIR_TYPE)
            ->whereNull("header.date_out")
            ->select("header.*",
                "CONFIG_WORKSHOP.workshop_name",
                "config.name as repair_type_name",
                "CONFIG_GENERAL_TABLES.name as section_in_name",
                "SEC_USERS.name as service_advisor")
            ->orderBy('header.created', 'desc')
            ->get();

    }

    public function getWorkShopPurchaseOfficeAndStore($workshop_code)
    {
        $stores = config("tables.table_names.stores");
        $purchaseOffices = config("tables.table_names.purchaseOffices");

        $data = DB::table("config_workshop")
            ->leftJoin("$stores", "config_workshop.store_code", "=", "$stores.code_store")
            ->leftJoin("$purchaseOffices", "config_workshop.area_code", "=", "$purchaseOffices.area")
            ->where("config_workshop.workshop_code", "=", $workshop_code)
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
    public function workOrderClosure(WorkOrderClosure $request): JsonResponse
    {
        $user = Auth::user();

        DB::beginTransaction();

        $workOrderNumber = $request->get("job_card_number");
        $workOrder = JobCardHeader::where("job_card_no", "=", $workOrderNumber)
            ->first();

        $vehicleHeader = VehicleHeader::where('registration_number', '=', $workOrder->reg_no);
        $vehicleHeader->status = StatusHelper::active();
        $vehicleHeader->save();

        $dataBefore = $workOrder->toArray();

        $workOrderNumber = $workOrder->job_card_no;

        $workOrder->status = StatusHelper::pendingApproval();
        $workOrder->date_out = Carbon::now();
        $workOrder->time_out = Carbon::now();
        $workOrder->dispatched_by = $user->staff_no;
        $workOrder->sub_fuel_level_out = '';
        $workOrder->millage_out = $request->get("exitOdometer");
        $workOrder->fuel_level_out = $request->get("fuel_level");
        $workOrder->driver_out = $request->get("driver_out");
        $workOrder->modified_by = $user->staff_no;
        $workOrder->status = StatusHelper::authorised();
        $workOrder->updated_at = Carbon::now();
        $totalWorkOrderAmount = $request->get('workOrderTotalAmount');

        foreach ($request->get("items") as $labourItem) {
            WorkshopLabour::create([
                'wshp_act_code' => $workOrder->wshp_act_code,
                'wshp_code' => $workOrder->workshop_code,
                'section' => $labourItem['workshopSection'],
                'evaluation' => 'N',
                'date_lab' => Carbon::now(),
                'mechanic' => $labourItem['mechanic'],
                'hours_worked' => $labourItem['hoursWorked'],
                'rate' => (float)$labourItem['ratePerHour'],
                'total_amount' => (float)$labourItem['totalAmount'],
                'def_no' => $labourItem['defect'],
                'created_by' => $user->staff_no,
                'type_of_hour' => $labourItem['shiftType'],
            ]);

            $totalWorkOrderAmount += (float)$labourItem['totalAmount'];
        }

        $workOrder->repair_cost = $totalWorkOrderAmount;

        $workOrder->save();

        $stockItemRequisitions = MaterialHeader::where('veh_reg_no', $workOrder->reg_no)
            ->whereIn('status', [StatusHelper::new(), StatusHelper::partiallyAuthorised()])
            ->where('item_type', '=', RequisitionItemTypes::StockItem)
            ->where('is_fuel', '=', 'N')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($stockItemRequisitions as $requisition) {
            $this->procurementService->cancelStoresRequisition($requisition->st_pur);
        }

        /*
         * $closureRemarks = $request->get('closureRemarks');
         *  $short_description = "$closureRemarks for work-order $workOrderNumber";
        $long_description = "$closureRemarks for work-order $workOrderNumber";
        $workflowProcess = WorkflowProcessCodes::WorkOrderClosure->value;
         * $this->workflowService->initiateWorkflowProcess(
            $workOrderNumber . "-C",
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $closureRemarks,
            $user,
            $totalWorkOrderAmount,
            $short_description,
            $long_description
        );*/

        DB::commit();

        WorkOrderCompleted::dispatch($workOrder, "fuel_requisition");

        return response()->json(
            [
                "success" => true,
                "payload" => [],
                "message" => "Work Order Closure, for Work Order with reference $workOrderNumber, has been Submitted For Approval",
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
            WorkshopLabour::firstOrCreate([
                'wshp_act_code' => $workOrder->wshp_act_code,
                'wshp_code' => $workOrder->workshop_code,
                'section' => $labourItem['workshopSection'],
                'evaluation' => 'N',
                'job_card_instruction' => $labourItem['jobCardInstruction'],
                'date_lab' => Carbon::now(),
                'mechanic' => $labourItem['mechanic'],
                'def_no' => $labourItem['assignedDefect'],
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
        $user = Auth::user();

        $recordId = $request->get("reassignmentReference");
        $assignmentRecord = WorkshopLabour::where("id", "=", $recordId)->first();
        $recordBefore = $assignmentRecord->toArray();
        DB::beginTransaction();
        /*foreach ($request->validated("items") as $labourItem) {
            WorkshopLabour::create([
                'wshp_act_code' => $workOrder->wshp_act_code,
                'wshp_code' => $workOrder->workshop_code,
                'section' => $labourItem['workshopSection'],
                'evaluation' => 'N',
                'job_card_instruction' => $labourItem['jobCardInstruction'],
                'date_lab' => Carbon::now(),
                'mechanic' => $labourItem['mechanic'],
                'def_no' => $labourItem['assignedDefect'],
                'defect_id' => $labourItem['assignedDefectId'],
                'created_by' => $user->staff_no,
            ]);
        }*/

        $assignmentRecord->mechanic = $request->validated('reassignTo');
        $assignmentRecord->section = $request->validated('reassignmentDefectSection');
        $assignmentRecord->section = $request->validated('reassignmentDefectSection');
        $assignmentRecord->updated_at = Carbon::now();

        $assignmentRecord->save();

        HistoryService::update($recordBefore, $assignmentRecord->toArray(), $assignmentRecord->wshp_act_code, 'Job Card Task Reassignment', $request->validated('reassignmentJustification'));

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
}
