<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Exceptions\DuplicateDefectException;
use App\Exceptions\MaterialReservationException;
use App\Exceptions\VehicleStateException;
use App\Exceptions\WorkflowTaskCreationFailedException;
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
use App\Services\Requisitions\FuelRequisitionService;
use App\Services\Requisitions\WorkshopRequisitionService;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\WorkShopManagement\JobCardDetailsService;
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
    private FuelRequisitionService $fuelRequisitionService;
    private WorkshopRequisitionService $workshopRequisitionService;
    private readonly JobCardDetailsService $jobCardDetailsService;

    public function __construct(WorkshopService                 $workshopService,
                                DocumentNumberGenerationService $numberGeneratorService,
                                FuelRequisitionService          $requisitionService,
                                WorkshopRequisitionService      $workshopRequisitionService,
                                JobCardDetailsService           $jobCardDetailsService)
    {
        $this->workshopService = $workshopService;
        $this->numberGeneratorService = $numberGeneratorService;
        $this->fuelRequisitionService = $requisitionService;
        $this->workshopRequisitionService = $workshopRequisitionService;
        $this->jobCardDetailsService = $jobCardDetailsService;
    }

    public function create(Request $request): View
    {
        $this->verifyRequestSignature($request);

        list(
            $repairTypes,
            $accessories_checked_in,
            $accessories,
            $details,
            $workshop_sections,
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
            $request->get("reference") ?? $request->get('ref'));

        $mechanics = [];
        if (!empty($details)) {
            $mechanics = Mechanic::where('status', '=', StatusHelper::active())
                ->where('workshop_code', '=', $details->workshop_code)
                ->get();
        }

        $step = $request->get("step") ?? 0;
        return view("modules.workshopManagement.workOrder.create")
            ->with(
                compact(
                    "repairTypes",
                    "accessories",
                    "details",
                    "accessories_checked_in",
                    "step",
                    "workshop_sections",
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
            $accessories_checked_in,
            $accessories,
            $details,
            $workshop_sections,
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
            $request->get("reference") ?? $request->get('ref')
        );

        $mechanics = [];
        if (!empty($details)) {
            $mechanics = Mechanic::where('status', '=', StatusHelper::active())
                ->where('workshop_code', '=', $details->workshop_code)
                ->get();
        }

        return view("modules.workshopManagement.workOrder.view")
            ->with(
                compact(
                    "repairTypes",
                    "accessories",
                    "details",
                    "accessories_checked_in",
                    "step",
                    "workshop_sections",
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
        $reference = $request->get("reference") ?? $request->get('ref');

        list(
            $repairTypes,
            $accessories_checked_in,
            $accessories,
            $details,
            $workshop_sections,
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
                    "accessories_checked_in",
                    "step",
                    "workshop_sections",
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

    public function createTaskForWorkShopSupervisor(SubmitJobCardToSupervisor $request): ?JsonResponse
    {
        try {
            return $this->workshopRequisitionService->createTaskForWorkShopSupervisor($request);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    ErrorMessages::getMessage("err_0005")
                )
            );
        }
    }

    public function show(Request $request): View
    {
        $this->verifyRequestSignature($request);

        $requestNumber = $request->get("ref");

        $user = Auth::user();

        [$header, $details] = $this->workshopRequisitionService->getWorkShopReservationDetails($requestNumber);

        $requestDetails = $header;

        if ($requestDetails == null) {
            abort(404);
        }

        $workflowTask = WorkflowTaskHeader::where("reference", "=", $requestNumber)->first();

        $requisitionTypes = RequisitionType::where("status", "01")->where("module", "FR")->get();

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
                "workflowTask"
            ));
    }

    public function showJobCard(Request $request): View|Application
    {
        $this->verifyRequestSignature($request);

        $step = $request->get("step") ?? 1;
        $reference = $request->get("reference") ?? $request->get('ref');

        list(
            $repairTypes,
            $accessories_checked_in,
            $accessories,
            $details,
            $workshop_sections,
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
                    "accessories_checked_in",
                    "step",
                    "workshop_sections",
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

        $reference = $request->get("reference") ?? $request->get('ref');
        $step = $request->get("step") ?? 0;

        list(
            $repairTypes,
            $accessories_checked_in,
            $accessories,
            $details,
            $workshop_sections,
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
                    "accessories_checked_in",
                    "step",
                    "workshop_sections",
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

    /*public function partsSelection(Request $request): View|Application|Factory
    {
        $step = '1';
        $repairTypes = [];
        $accessoriesCheckedIn = [];
        $accessories = [];
        $details = [];
        $workshopSections = [];
        $defects = [];
        $comments = [];

        return view('modules.workshopManagement.workOrder.create_old')->with(
            compact(
                'repairTypes',
                'accessories',
                'details',
                'accessoriesCheckedIn',
                'step',
                'workshopSections',
                'defects',
                'comments'
            )
        );
    }*/

    /*public function defectsTab(Request $request): View|RedirectResponse
    {
        $this->verifyRequestSignature($request);
        $step = $request->get("step") ?? 0;
        $reference = $request->get("reference") ?? $request->get('ref');

        list(
            $repairTypes,
            $accessories_checked_in,
            $accessories,
            $details,
            $workshop_sections,
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
                    "accessories_checked_in",
                    "step",
                    "workshop_sections",
                    "defects",
                    "comments",
                    "officeDetails",
                    "materials",
                    "materialsHeader",
                    "services",
                    "labour",
                    "pettyCashItems",
                    'observation'
                )
            );
    }*/

    /* public function openJobCardClosure(Request $request): View
    {
        $this->verifyRequestSignature($request);

        $jobCardClosureReferenceNumber = str_replace('-C', '', $request->get('ref'));

        $step = $request->get("step") ?? 0;

        list(
            $repairTypes,
            $accessories_checked_in,
            $accessories,
            $details,
            $workshop_sections,
            $defects,
            $comments,
            $officeDetails,
            $materials,
            $materialsHeader,
            $services,
            $labour,
            $pettyCashItems,
            $observation
            ) = $this->jobCardDetailsService->getFullJobCardDetails($jobCardClosureReferenceNumber);

        $taskHeader = null;
        if ($request->get("ref")) {
            $taskHeader = WorkflowTaskHeader::where('reference', '=', $request->get('ref'))->first();
        }
        $approvalHistory = [];

        return view("modules.workshopManagement.workOrder.exitFromWorkshop")
            ->with(
                compact(
                    "repairTypes",
                    "accessories",
                    "details",
                    "accessories_checked_in",
                    "step",
                    "workshop_sections",
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
                    'observation'
                )
            );
    }*/

    public function exitWorkShop(Request $request): View
    {
        $isValidSignature = $request->hasValidSignature();

        if (!$isValidSignature) {
            abort(401);
        }

        $step = $request->get("step") ?? 0;

        list(
            $repairTypes,
            $accessories_checked_in,
            $accessories,
            $details,
            $workshop_sections,
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
        if ($request->get("reference")) {
            $taskHeader = WorkflowTaskHeader::where('reference', '=', $request->get("reference"))->first();
        }

        $mechanics = [];
        if (!empty($details)) {
            $mechanics = Mechanic::where('status', '=', StatusHelper::active())
                ->where('workshop_code', '=', $details->workshop_code)
                ->get();
        }

        return view("modules.workshopManagement.workOrder.exitFromWorkshop")
            ->with(
                compact(
                    "repairTypes",
                    "accessories",
                    "details",
                    "accessories_checked_in",
                    "step",
                    "workshop_sections",
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
            [
                'success' => true,
                'payload' => GeneralTable::where(Constants::TYPE_KEY, ConfigurationTypes::FUEL_LEVELS->value)
                    ->get()
            ]
        /*FleetMasterJsonResponse::response(
            'success',
            true,
            null,
            GeneralTable::where(Constants::TYPE_KEY, ConfigurationTypes::FUEL_LEVELS->value)
                ->get()
        )*/
        );
    }

    public function saveJobCardHeader(JobCardRequest $request): JsonResponse
    {
        try {
            $response = $this->workshopService->createJobCard($request);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
                    true,
                    null,
                    null,
                    URL::signedRoute("vehicle.workshop.checkin", [
                        "step" => 2,
                        "reference" => $response->job_card_no
                    ])
                )
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    ErrorMessages::getMessage("err_0005"),

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
            if ($e instanceof MaterialReservationException
                || $e instanceof WorkflowTaskCreationFailedException
                || $e instanceof VehicleStateException) {
                $message = $e->getMessage();
            } else {
                Log::error($e);
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    $message
                )
            );
        }
    }

    public function saveJobCardAccessories(Request $request): JsonResponse
    {
        try {
            $this->workshopService->createJobCardAccessories($request);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
                    true,
                    SystemMessages::accessoriesCheckedIn(),
                    null,
                    URL::signedRoute("vehicle.workshop.checkin",
                        ["step" => 3, "reference" => $request->get("job_card_voucher")])
                )
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    ErrorMessages::getMessage("err_0005")
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
                    'success',
                    true,
                    SystemMessages::defectRecorded(),
                    null,
                    URL::signedRoute("show.job.card",
                        ["step" => 4, "reference" => $request->get("job_card_no")]
                    )
                )
            );

        } catch (\Exception $e) {
            $message = ErrorMessages::getMessage("err_0005");
            if ($e instanceof MaterialReservationException
                || $e instanceof WorkflowTaskCreationFailedException
                || $e instanceof DuplicateDefectException
                || $e instanceof VehicleStateException) {
                $message = $e->getMessage();
                Log::info($e);
            } else {
                Log::error($e);
            }
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    $message
                )
            );
        }
    }

    public function saveJobCardWorkAssignments(JobCardTaskAssignment $request): JsonResponse
    {
        try {
            return $this->workshopService->saveJobCardWorkAssignments($request);
        } catch (\Exception $e) {
            $message = ErrorMessages::getMessage("err_0005");
            if ($e instanceof MaterialReservationException
                || $e instanceof WorkflowTaskCreationFailedException
                || $e instanceof VehicleStateException) {
                $message = $e->getMessage();
            } else {
                Log::error($e);
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
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
            if ($e instanceof MaterialReservationException
                || $e instanceof WorkflowTaskCreationFailedException
                || $e instanceof VehicleStateException) {
                $message = $e->getMessage();
            } else {
                Log::error($e);
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
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
        list($repairTypes, $accessories, $workshop_sections) =
            $this->jobCardDetailsService->getWorkshopsRepairTypesAndSections();
        $accessories_checked_in = null;
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
            list($accessories_checked_in,
                $details,
                $officeDetails,
                $defects,
                $comments,
                $materials,
                $materialsHeader,
                $services,
                $labour,
                $pettyCashItems,
                $observation) = $this->getFullJobCardData($reference);
        }

        return array(
            $step,
            $repairTypes,
            $accessories_checked_in,
            $accessories,
            $details,
            $workshop_sections,
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
        Log::info($request->has("workshop_code"));
        try {
            if (!$request->has("workshop_code")) {
                return response()->json(
                    FleetMasterJsonResponse::response(
                        'failure',
                        false,
                        null
                    )
                );
            }

            $workshopCode = $request->get("workshop_code");
            Log::info($workshopCode);

            Log::info("Value Received " . $workshopCode);

            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
                    true,
                    null,
                    $this->workshopService->getWorkShopPurchaseOfficeAndStore($workshopCode)
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
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
                    'failure',
                    false,
                    null
                )
            );
        }

        $vehicleRegistration = $request->get('vehicleRegistration');
        Log::info("Checking for reservations for $vehicleRegistration");
        $details = $this->workshopService->getReservedMaterialsAndServices($vehicleRegistration);

        return response()->json(
            FleetMasterJsonResponse::response(
                'success',
                true,
                null,
                $details
            )
        );
    }
}
