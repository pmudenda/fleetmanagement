<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\ErrorMessages;
use App\Constants\QueryComparisonOperator;
use App\Constants\SystemMessages;
use App\Constants\TableColumns;
use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Enums\ResponseState;
use App\Exceptions\BaseException;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\OrganisationUnitStateException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkShopManagement\JobCardRequest;
use App\Http\Requests\WorkShopManagement\JobCardTaskAssignment;
use App\Http\Requests\WorkShopManagement\JobCardTaskReassignment;
use App\Http\Requests\WorkShopManagement\SubmitJobCardToSupervisor;
use App\Http\Requests\WorkShopManagement\VehicleDefects;
use App\Http\Requests\WorkShopManagement\WorkOrderClosure;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Common\MaterialHeader;
use App\Models\RequisitionType;
use App\Models\Settings\GeneralTable;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Models\WorkShopManagement\Mechanic;
use App\Services\Security\ProfileDelegationService;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\WorkShopManagement\JobCardDetailsService;
use App\Services\WorkShopManagement\WorkshopRequisitionService;
use App\Services\WorkShopManagement\WorkshopService;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    private WorkshopService $workshopService;
    private DocumentNumberGenerationService $numberGeneratorService;
    private WorkshopRequisitionService $workshopRequisitionService;
    private readonly JobCardDetailsService $jobCardDetailsService;
    private ProfileDelegationService $profileDelegationService;

    public function __construct(WorkshopService                 $workshopService,
                                DocumentNumberGenerationService $numberGeneratorService,
                                WorkshopRequisitionService      $workshopRequisitionService,
                                JobCardDetailsService           $jobCardDetailsService,
                                ProfileDelegationService        $profileDelegationService
    )
    {
        $this->workshopService = $workshopService;
        $this->numberGeneratorService = $numberGeneratorService;
        $this->workshopRequisitionService = $workshopRequisitionService;
        $this->jobCardDetailsService = $jobCardDetailsService;
        $this->profileDelegationService = $profileDelegationService;
    }

    public function create(Request $request): View
    {
        $this->verifyRequestSignature($request);

        list(
            $repairTypes,
            $accessoriesCheckedIn,
            $accessories,
            $details,
            $workshopSections,
            $defects,
            $comments,
            $officeDetails,
            $materials,
            $materialsHeader,
            $services,
            $labour,
            $pettyCashItems,
            $observation
            ) = $this->jobCardDetailsService->getFullJobCardDetails(
            $request->get(TableColumns::REFERENCE) ?? $request->get('ref')
        );

        $mechanics = [];
        if (!empty($details)) {
            Log::debug("Fetching Mechanic For $details->workshop_code");
            $mechanics = Mechanic::where(
                TableColumns::STATUS,
                QueryComparisonOperator::EQUALS,
                StatusHelper::active())
                ->where('workshop_code',
                    QueryComparisonOperator::EQUALS,
                    $details->workshop_code)
                ->get();
        }

        $step = $request->get("step") ?? 0;
        return view("modules.workshopManagement.workOrder.create")
            ->with(
                compact(
                    "repairTypes",
                    "accessories",
                    "details",
                    "accessoriesCheckedIn",
                    "step",
                    "workshopSections",
                    "defects",
                    "comments",
                    "officeDetails",
                    "materials",
                    "materialsHeader",
                    "services",
                    'labour',
                    'mechanics',
                    'pettyCashItems',
                    'observation'
                )
            );
    }

    public function view(Request $request): View
    {
        $this->verifyRequestSignature($request);
        $step = $request->get("step") ?? 0;

        list(
            $repairTypes,
            $accessoriesCheckedIn,
            $accessories,
            $details,
            $workshopSections,
            $defects,
            $comments,
            $officeDetails,
            $materials,
            $materialsHeader,
            $services,
            $labour,
            $pettyCashItems,
            $observation
            ) = $this->jobCardDetailsService->getFullJobCardDetails(
            $request->get(TableColumns::REFERENCE) ?? $request->get('ref')
        );

        $mechanics = [];
        if (!empty($details)) {
            $mechanics = Mechanic::where(TableColumns::STATUS,
                QueryComparisonOperator::EQUALS,
                StatusHelper::active())
                ->where('workshop_code',
                    QueryComparisonOperator::EQUALS,
                    $details->workshop_code)
                ->get();
        }

        return view("modules.workshopManagement.workOrder.view")
            ->with(
                compact(
                    "repairTypes",
                    "accessories",
                    "details",
                    "accessoriesCheckedIn",
                    "step",
                    "workshopSections",
                    "defects",
                    "comments",
                    "officeDetails",
                    "materials",
                    "materialsHeader",
                    "services",
                    'labour',
                    'mechanics',
                    'pettyCashItems',
                    'observation'
                )
            );
    }


    /**
     * Method is used by workshop front-desk
     * @param Request $request
     * @return View
     */
    public function start(Request $request): View
    {
        $step = $request->get("step") ?? 0;
        $reference = $request->get(TableColumns::REFERENCE) ?? $request->get('ref');

        list(
            $repairTypes,
            $accessoriesCheckedIn,
            $accessories,
            $details,
            $workshopSections,
            $defects,
            $comments,
            $officeDetails,
            $materials,
            $materialsHeader,
            $services,
            $labour,
            $pettyCashItems,
            $observation
            ) = $this->jobCardDetailsService->getFullJobCardDetails($reference);

        return view('modules.workshopManagement.workOrder.start')
            ->with(
                compact(
                    "repairTypes",
                    "accessories",
                    "details",
                    "accessoriesCheckedIn",
                    "step",
                    "workshopSections",
                    "defects",
                    "comments",
                    "officeDetails",
                    "materials",
                    "materialsHeader",
                    "services",
                    'labour',
                    'pettyCashItems',
                    'observation'
                )
            );
    }

    public function task(SubmitJobCardToSupervisor $request): ?JsonResponse
    {
        try {
            return $this->jobCardDetailsService->createTaskForWorkShopSupervisor($request);
        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage("err_0005");

            if ($e instanceof DataNotFoundException) {
                $message = $e->getMessage();
            }

            return response()->json(

                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    $message,
                )
            );
        }
    }

    public function show(Request $request): View
    {

        $this->verifyRequestSignature($request);

        $requestNumber = $request->get("ref");

        $user = Auth::user();

        $delegatedProfileOwner = $this->profileDelegationService->getDelegatedProfileOwner($user->id);

        [$header, $details] = $this->workshopRequisitionService->getWorkShopReservationDetails($requestNumber);

        $requestDetails = $header;

        if ($requestDetails == null) {
            abort(404);
        }

        $workflowTask = WorkflowTaskHeader::where(
            TableColumns::REFERENCE,
            QueryComparisonOperator::EQUALS,
            $requestNumber
        )->first();

        $requisitionTypes = RequisitionType::where(
            TableColumns::STATUS,
            QueryComparisonOperator::EQUALS,
            StatusHelper::active()
        )->where("module", "FR")
            ->get();

        $daysToNextRefuel = config("settings.fuel_requisition_validity");

        $approvalHistory = [];

        return view("modules.workshopManagement.reservation.show")
            ->with(compact(
                "user",
                "requisitionTypes",
                "requestDetails",
                "details",
                "daysToNextRefuel",
                "approvalHistory",
                "workflowTask",
                "delegatedProfileOwner"
            ));
    }

    public function showJobCard(Request $request): View|Application
    {
        $this->verifyRequestSignature($request);

        $step = $request->get("step") ?? 1;
        $reference = $request->get(TableColumns::REFERENCE) ?? $request->get('ref');

        list(
            $repairTypes,
            $accessoriesCheckedIn,
            $accessories,
            $details,
            $workshopSections,
            $defects,
            $comments,
            $officeDetails,
            $materials,
            $materialsHeader,
            $services,
            $labour,
            $pettyCashItems,
            $observation
            ) = $this->jobCardDetailsService->getFullJobCardDetails($reference);

        return view("modules.workshopManagement.workOrder.show")
            ->with(
                compact(
                    "repairTypes",
                    "accessories",
                    "details",
                    "accessoriesCheckedIn",
                    "step",
                    "workshopSections",
                    "defects",
                    "comments",
                    "officeDetails",
                    "materials",
                    "materialsHeader",
                    "services",
                    'labour',
                    'pettyCashItems',
                    'observation'
                )
            );
    }

    public function showAccessoriesTab(Request $request): View
    {
        $this->verifyRequestSignature($request);

        if (!$request->has("step")) {
            return redirect(URL::signedRoute("show.job.card", ["step" => 1]));
        }

        $reference = $request->get(TableColumns::REFERENCE) ?? $request->get('ref');
        $step = $request->get("step") ?? 0;


        list(
            $repairTypes,
            $accessoriesCheckedIn,
            $accessories,
            $details,
            $workshopSections,
            $defects,
            $comments,
            $officeDetails,
            $materials,
            $materialsHeader,
            $services,
            $labour,
            $pettyCashItems,
            $observation
            ) = $this->jobCardDetailsService->getFullJobCardDetails($reference);

        return view("modules.workshopManagement.workOrder.create")
            ->with(
                compact(
                    "repairTypes",
                    "accessories",
                    "details",
                    "accessoriesCheckedIn",
                    "step",
                    "workshopSections",
                    "defects",
                    "comments",
                    "officeDetails",
                    "materials",
                    "materialsHeader",
                    "services",
                    'labour',
                    'pettyCashItems',
                    'observation'
                )
            );
    }

    public function exitWorkShop(Request $request): View
    {
        $isValidSignature = $request->hasValidSignature();

        if (!$isValidSignature) {
            abort(401);
        }

        $step = $request->get("step") ?? 0;

        list(
            $repairTypes,
            $accessoriesCheckedIn,
            $accessories,
            $details,
            $workshopSections,
            $defects,
            $comments,
            $officeDetails,
            $materials,
            $materialsHeader,
            $services,
            $labour,
            $pettyCashItems,
            $observation
            ) = $this->jobCardDetailsService->getFullJobCardDetails($request->get("reference"));
        $taskHeader = null;
        $approvalHistory = [];
        if ($request->get(TableColumns::REFERENCE)) {
            $taskHeader = WorkflowTaskHeader::where(
                'reference',
                QueryComparisonOperator::EQUALS,
                $request->get("reference")
            )->first();
        }

        $mechanics = [];
        if (!empty($details)) {
            $mechanics = Mechanic::where(TableColumns::STATUS,
                QueryComparisonOperator::EQUALS,
                StatusHelper::active())
                ->where('workshop_code',
                    QueryComparisonOperator::EQUALS,
                    $details->workshop_code)
                ->get();
        }

        return view("modules.workshopManagement.workOrder.exitFromWorkshop")
            ->with(
                compact(
                    "repairTypes",
                    "accessories",
                    "details",
                    "accessoriesCheckedIn",
                    "step",
                    "workshopSections",
                    "defects",
                    "comments",
                    "officeDetails",
                    "materials",
                    "materialsHeader",
                    "services",
                    "labour",
                    'taskHeader',
                    'approvalHistory',
                    'pettyCashItems',
                    'observation',
                    'mechanics'
                )
            );
    }

    public function getFuelLevels(): JsonResponse
    {
        return response()->json(
            FleetMasterJsonResponse::response(
                ResponseState::SUCCESS->value,
                true,
                null,
                GeneralTable::where(Constants::TYPE_KEY, ConfigurationTypes::FUEL_LEVELS->value)
                    ->get()
            )
        );
    }

    public function saveJobCardHeader(JobCardRequest $request): JsonResponse
    {
        try {
            Log::debug("Logging Job Card");
            $response = $this->workshopService->createJobCard($request);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    null,
                    null,
                    URL::signedRoute("vehicle.workshop.checkin", [
                        "step" => 2,
                        TableColumns::REFERENCE => $response->job_card_no
                    ])
                )
            );
        } catch (\Exception $e) {

            $message = ErrorMessages::getMessage('err_0005');
            Log::error($e);
            if ($e instanceof BaseException) {
                $message = $e->getMessage();
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    $message,
                )
            );
        }
    }

    public function closeJobCard(WorkOrderClosure $request): JsonResponse
    {
        try {
            return $this->workshopService->closeJobCard($request);
        } catch (\Exception $e) {
            $message = ErrorMessages::getMessage("err_0005");
            if ($e instanceof BaseException) {
                $message = $e->getMessage();
            } else {
                Log::error($e);
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    $message
                )
            );
        }
    }

    public function saveJobCardDefects(VehicleDefects $request): JsonResponse
    {
        try {
            $this->workshopService->createJobCardDefects($request);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    SystemMessages::defectRecorded(),
                    null,
                    URL::signedRoute("show.job.card",
                        [
                            "step" => 4,
                            TableColumns::REFERENCE => $request->get("job_card_no")
                        ]
                    )
                )
            );

        } catch (\Exception $e) {
            $message = ErrorMessages::getMessage("err_0005");

            if ($e instanceof BaseException) {
                $message = $e->getMessage();
            } else {
                Log::error($e);
            }
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    $message
                )
            );
        }
    }

    public function saveJobCardWorkAssignments(JobCardTaskAssignment $request): JsonResponse
    {
        try {
            $this->workshopService->saveJobCardWorkAssignments($request);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    SystemMessages::JOBCARD_TASKS_ASSIGNMENTS,
                    [],
                    URL::signedRoute("workOrder.list")
                )
            );
        } catch (\Exception $e) {
            $message = ErrorMessages::getMessage("err_0005");

            if ($e instanceof BaseException) {
                $message = $e->getMessage();
            } else {
                Log::error($e);
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    $message
                )
            );
        }
    }

    public function saveJobCardWorkReassignments(JobCardTaskReassignment $request): JsonResponse
    {
        try {
            return $this->workshopService->saveJobCardWorkReassignments($request);
        } catch (\Exception $e) {
            $message = ErrorMessages::getMessage("err_0005");
            if ($e instanceof BaseException) {
                $message = $e->getMessage();
            } else {
                Log::error($e);
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    $message
                )
            );
        }
    }

    /**
     * @param $reference
     * @param $step
     * @return array
     */
    public function getJobCardCreationData($reference, $step): array
    {
        list($repairTypes, $accessories, $workshopSections) =
            $this->jobCardDetailsService->getWorkshopsRepairTypesAndSections();

        $accessoriesCheckedIn = null;
        $details = null;
        $defects = collect([]);
        $comments = [];
        $officeDetails = null;
        $materials = collect([]);
        $materialsHeader = null;
        $services = collect([]);
        $labour = collect([]);
        $pettyCashItems = collect([]);
        $observation = collect([]);

        if ($reference) {
            list($accessoriesCheckedIn,
                $details,
                $officeDetails,
                $defects,
                $comments,
                $materials,
                $materialsHeader,
                $services,
                $labour,
                $pettyCashItems,
                $observation) = $this->jobCardDetailsService->getFullJobCardData($reference);
        }

        return array(
            $step,
            $repairTypes,
            $accessoriesCheckedIn,
            $accessories,
            $details,
            $workshopSections,
            $defects,
            $comments,
            $officeDetails,
            $materials,
            $materialsHeader,
            $services,
            $labour,
            $pettyCashItems,
            $observation
        );
    }


    public function deLinkJobCardForm()
    {
        return view('modules.workshopManagement.delinkJobCard');
    }

    public function deLinkedJobCard(Request $request)
    {
        $stPur = $request->query('st_pur');

        $results = DB::select("
        SELECT DISTINCT gh.PROC_REF,
               cs.name AS status,
               gh.DATE_CREATED,
               gh.VALID_DATE_TO,
               gh.VEH_REG_NO,
               gh.req_no, 
               gh.document_no AS job_card_no
        FROM fleetmaster.gen_material_headers gh,
             fleetmaster.gen_material_details gd,
             fleetmaster.config_statuses cs
        WHERE gh.req_no = gd.req_no
          AND gh.status = cs.code
          AND cs.module = 'MAT'
          AND gh.st_pur = ?
          AND gh.proc_ref LIKE 'C0%'
          AND gh.is_fuel = 'N'
        ORDER BY gh.date_created DESC
    ", [$stPur]);
        return view('modules.workshopManagement.delinkedJobCardDetails', ['results' => $results]);
    }

    public function delinkPRSearch(Request $request)
    {
        $request->validate([
            'st_pur' => 'nullable|string|size:12',
        ]);

        $query = DB::table('fleetmaster.gen_material_headers as gh')
            ->distinct()
            ->select(
                'gh.PROC_REF',
                'gh.status as status_code',
                'cs.name as status',
                'gh.DATE_CREATED',
                'gh.VALID_DATE_TO',
                'gh.VEH_REG_NO',
                'gh.req_no',
                'gh.document_no as job_card_no',
                'jc.DRIVER_IN',
                'jc.DATE_IN',
                'jc.DATE_OUT'
            )
            ->join('fleetmaster.gen_material_details as gd', 'gh.req_no', '=', 'gd.req_no')
            ->leftJoin('FLEETMASTER.WM_JOB_CARD_HEADER as jc', 'gh.document_no', '=', 'jc.JOB_CARD_NO')
            ->join('FLEETMASTER.config_statuses as cs', function ($join) {
                $join->on('gh.status', '=', 'cs.code')
                    ->where('cs.module', 'MAT');
            })
            ->where('gh.is_fuel', 'N')
            ->where('gh.proc_ref', 'LIKE', 'C0%');

        if ($request->filled('st_pur')) {
            $query->where('gh.st_pur', $request->input('st_pur'));
        }

        $result = $query->orderBy('gh.date_created', 'desc')->first();

        \Log::info('Query Result:', ['result' => $result ? (array)$result : 'No result']);

        if ($result) {
            return response()->json([
                'success' => true,
                'PROC_REF' => $result->proc_ref,
                'status_code' => $result->status_code,
                'status' => $result->status,
                'DATE_CREATED' => $result->date_created,
                'VALID_DATE_TO' => $result->valid_date_to,
                'VEH_REG_NO' => $result->veh_reg_no,
                'req_no' => $result->req_no,
                'job_card_no' => $result->job_card_no,
                'DRIVER_IN' => $result->driver_in,
                'DATE_IN' => $result->date_in,
                'DATE_OUT' => $result->date_out
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No requisition found with the provided filters.'
            ]);
        }
    }

    public function retrieveDelinkedPO(Request $request)
    {
        $request->validate([
            'st_pur' => 'nullable|string|size:12',
        ]);

        $query = DB::table('fleetmaster.gen_material_headers as gh')
            ->distinct()
            ->select(
                'gh.PROC_REF',
                'gh.status as status_code',
                'cs.name as status',
                'gh.DATE_CREATED',
                'gh.VALID_DATE_TO',
                'gh.VEH_REG_NO',
                'gh.req_no',
                'gh.document_no as job_card_no',
                'jc.DRIVER_IN',
                'jc.DATE_IN',
                'jc.DATE_OUT'
            )
            ->join('fleetmaster.gen_material_details as gd', 'gh.req_no', '=', 'gd.req_no')
            ->join('FLEETMASTER.WM_JOB_CARD_HEADER as jc', 'gh.document_no', '=', 'jc.JOB_CARD_NO')
            ->join('FLEETMASTER.config_statuses as cs', 'gh.status', '=', 'cs.code')
            ->where('cs.module', 'MAT')
            ->whereNotNull('gh.document_no')
            ->where('gh.is_fuel', 'N');

        if ($request->filled('st_pur')) {
            $query->where('gh.st_pur', $request->input('st_pur'));
        }else {
            $query->where('gh.proc_ref', 'LIKE', 'C0%');
        }

        $result = $query->orderBy('gh.date_created', 'desc')->first();

        // Log the raw result to debug
        \Log::info('Query Result, this is the Result:', ['result' => $result ? (array)$result : 'No result']);

        if ($result) {
            return response()->json([
                'success' => true,
                'PROC_REF' => $result->proc_ref,
                'status_code' => $result->status_code,
                'status' => $result->status,
                'DATE_CREATED' => $result->date_created,
                'VALID_DATE_TO' => $result->valid_date_to,
                'VEH_REG_NO' => $result->veh_reg_no,
                'req_no' => $result->req_no,
                'job_card_no' => $result->job_card_no,
                'DRIVER_IN' => $result->driver_in,
                'DATE_IN' => $result->date_in,
                'DATE_OUT' => $result->date_out
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No requisition found with the provided filters.'
            ]);
        }
    }

    public function delinkJobCard(Request $request)
    {
        $request->validate([
            'st_pur' => 'required|string|size:12',
            'justification' => 'required|string|max:255',
        ]);

        $stPur = $request->input('st_pur');
        $justification = $request->input('justification');

        $affectedRows = DB::table('fleetmaster.gen_material_headers')
            ->where('st_pur', $stPur)
            ->update([
                'document_no' => null,
                'justification_rejection' => $justification
            ]);

        if ($affectedRows > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Purchase Order successfully delinked from Job Card.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No matching requisition found to delink.'
            ], 404);
        }
    }

    public function getStoreAndPurchaseOffice(Request $request): JsonResponse
    {
        Log::debug($request->has("workshop_code"));
        try {
            if (!$request->has("workshop_code")) {
                return response()->json(
                    FleetMasterJsonResponse::response(
                        ResponseState::FAILURE->value,
                        false,
                        null
                    )
                );
            }

            $workshopCode = $request->get("workshop_code");
            Log::debug($workshopCode);
            Log::debug("Value Received " . $workshopCode);

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    null,
                    $this->workshopService->getWorkShopPurchaseOfficeAndStore($workshopCode)
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    null
                )
            );
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    public function verifyRequestSignature(Request $request): void
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
    }

    public function getReservedMaterialAndServices(Request $request): JsonResponse
    {
        if (!$request->has('vehicleRegistration')) {
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    null
                )
            );
        }

        $vehicleRegistration = $request->get('vehicleRegistration');
        Log::debug("Checking for reservations for $vehicleRegistration");
        try{
            $details = $this->workshopService->getReservedMaterialsAndServices($vehicleRegistration);

        }catch(Exception $exception){
            dd($exception->getMessage());
        }
        return response()->json(
            FleetMasterJsonResponse::response(
                ResponseState::SUCCESS->value,
                true,
                null,
                $details
            )
        );
    }

    public function reject()
    {
        return view('modules.workshopManagement.storesRequisitions');
    }

    public function fetchRequisitionDetails(Request $request): JsonResponse
    {
        // Validate the input
        $validatedData = $request->validate([
            'requisitionNumber' => 'required|string'
        ]);

        $requisitionNumber = $validatedData['requisitionNumber'];

        // Fetch data from the database
        $requisition = MaterialHeader::where('st_pur', $requisitionNumber)->first();

//        $requisition = DB::table('fleetmaster.gen_material_headers AS H')
//            ->join('fleetmaster.config_statuses AS cs', 'H.STATUS', '=', 'cs.CODE')
//            ->select('H.ST_PUR', 'cs.name', 'H.veh_reg_no', 'H.form_order', 'H.status', 'H.comments')
//            ->where('H.ST_PUR', 'like', $requisitionNumber . '%')
//            ->whereIn('cs.CODE', ['02', '26'])
//            ->where('cs.module', 'MAT')
//            ->first();
//
//        if ($requisition) {
//            return response()->json([
//                'success' => true,
//                'veh_reg_no' => $requisition->veh_reg_no,
//                'form_order' => $requisition->form_order,
//                'status' => $requisition->status,
//                'comments' => $requisition->comments,
//                'status_name' => $requisition->name,
//            ]);
//        }

        $requisition = DB::table('fleetmaster.gen_material_headers AS H')
            ->join('fleetmaster.gen_material_details AS d', 'H.req_no', '=', 'd.req_no')
            ->join('fleetmaster.config_statuses AS cs', 'H.STATUS', '=', 'cs.CODE')
            ->select('H.ST_PUR', 'cs.name', 'd.REG_NO', 'H.comments')
            ->where('H.ST_PUR', '=', $requisitionNumber)
            ->whereIn('H.status', ['02', '26'])
            ->where('H.is_fuel', 'N')
            ->where('cs.module', 'MAT')
            ->distinct()
            ->first();
        Log::info('Requisition Data:', (array) $requisition);

        if ($requisition) {
            return response()->json([
                'success' => true,
                'veh_reg_no' => $requisition->reg_no ?? '',
                'status_name' => $requisition->name,
                'comments' => $requisition->comments,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No requisition found with the provided number.',
        ], 404);
    }

    public function rejectRequisition(Request $request): JsonResponse
    {
        $requisitionId = $request->input('requisitionId');
        $justification = $request->input('justification');

        try {
            DB::beginTransaction();

            // Update ZFMS table
            DB::table('fleetmaster.gen_material_headers')
                ->where('st_pur', $requisitionId)
                ->update([
                    'STATUS' => '03',
                    'JUSTIFICATION_REJECTION' => $justification
                ]);

            // Update SPMS table
            DB::table('store_requisitions_header')
                ->where('document_no', $requisitionId)
                ->update([
                    'STATUS' => '03',
                    'JUSTIFICATION_REJECTION' => $justification
                ]);

            DB::commit();

            return response()->json(['message' => 'Requisition rejected successfully!'], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error details
            Log::error('Failed to reject requisition', [
                'requisitionId' => $requisitionId,
                'justification' => $justification,
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);

            return response()->json(['message' => 'Failed to reject requisition.'], 500);
        }
    }}
