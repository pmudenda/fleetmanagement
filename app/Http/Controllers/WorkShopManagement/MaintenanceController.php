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
            $mechanics = Mechanic::where( TableColumns::STATUS,
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
                    $message
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
        $details = $this->workshopService->getReservedMaterialsAndServices($vehicleRegistration);

        return response()->json(
            FleetMasterJsonResponse::response(
                ResponseState::SUCCESS->value,
                true,
                null,
                $details
            )
        );
    }
}
