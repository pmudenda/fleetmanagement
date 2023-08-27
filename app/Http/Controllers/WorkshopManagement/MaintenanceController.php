<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Enums\RequisitionItemTypes;
use App\Exceptions\DuplicateDefectException;
use App\Exceptions\InvalidAssessmentSignatoryException;
use App\Exceptions\MaterialReservationException;
use App\Exceptions\VehicleStateException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleDefectsRequest;
use App\Http\Requests\WorkShopManagement\JobCardRequest;
use App\Http\Requests\WorkShopManagement\JobCardTaskAssignment;
use App\Http\Requests\WorkShopManagement\JobCardTaskReassignment;
use App\Http\Requests\WorkShopManagement\PettyCashItems;
use App\Http\Requests\WorkShopManagement\SubmitJobCardToSupervisor;
use App\Http\Requests\WorkShopManagement\WorkOrderClosure;
use App\Http\Requests\WorkShopManagement\WorkshopMaterialResevationRequest;
use App\Http\Requests\WorkShopManagement\WorkshopRequisitionRequest;
use App\Http\Requests\WorkShopManagement\WorkshopServiceRequisitionRequest;
use App\Http\Requests\WorkShopManagement\WorkshopServiceReservationRequest;
use App\Models\Driver;
use App\Models\MaterialDetail;
use App\Models\MaterialHeader;
use App\Models\Reference\PHCMSEmployee;
use App\Models\RequisitionType;
use App\Models\Settings\Accessory;
use App\Models\Settings\GeneralTable;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Models\WorkShopManagement\AssessmentObservation;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Models\WorkShopManagement\Mechanic;
use App\Models\WorkShopManagement\WorkShopComment;
use App\Models\WorkShopManagement\WorkShopMaterial;
use App\Models\WorkShopManagement\WorkShopMaterialHeader;
use App\Models\WorkShopManagement\WorkShopServiceModel;
use App\Models\WorkShopManagement\WorkShopVehicleAccessory;
use App\Models\WorkShopManagement\WorkShopVehicleDefect;
use App\Services\Requisitions\FuelRequisitionService;
use App\Services\Requisitions\WorkshopRequisitionService;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\WorkShopManagement\WorkshopService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    const RECORD_NOT_FOUND = "Record Not Found";
    const RECORD_REMOVED_SUCCESSFULLY = "Record Removed Successfully";
    private WorkshopService $workshopService;
    private DocumentNumberGenerationService $numberGeneratorService;
    private FuelRequisitionService $fuelRequisitionService;
    private WorkshopRequisitionService $workshopRequisitionService;

    public function __construct(WorkshopService                 $workshopService,
                                DocumentNumberGenerationService $numberGeneratorService,
                                FuelRequisitionService          $requisitionService,
                                WorkshopRequisitionService      $workshopRequisitionService)
    {
        $this->workshopService = $workshopService;
        $this->numberGeneratorService = $numberGeneratorService;
        $this->fuelRequisitionService = $requisitionService;
        $this->workshopRequisitionService = $workshopRequisitionService;
    }

    public function list(Request $request): View
    {
        $this->verifyRequestSignature($request);

        $workshopsVehicleList = $this->workshopService->getJobCardHeader();

        return view("modules.workshopManagement.vehiclesInWorkshop")
            ->with(
                compact(
                    "workshopsVehicleList"
                )
            );
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
            ) = $this->getFullJobCardDetails($request->get("reference") ?? $request->get('ref'));

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
            ) = $this->getFullJobCardDetails($request->get("reference") ?? $request->get('ref'));

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
            ) = $this->getFullJobCardDetails($reference);

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

    public function defectsTab(Request $request): View|RedirectResponse
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
            ) = $this->getFullJobCardDetails($reference);

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
    }

    public function createTaskForWorkShopSupervisor(SubmitJobCardToSupervisor $request): ?JsonResponse
    {
        try {
            return $this->workshopRequisitionService->createTaskForWorkShopSupervisor($request);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                "success" => false,
                "items" => [],
                "total_count" => 0,
                "message" => ErrorMessages::getMessage("err_0005")
            ]);
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

    public function searchArticle(Request $request): JsonResponse
    {
        try {

            if (empty($request->get("type_article"))) {
                return response()->json([
                    "success" => false,
                    "items" => [],
                    "total_count" => 0
                ]);
            }

            $search = trim(strtoupper($request->get("search")));

            $query = $this->getArticlesQueryBuilder($request);

            $stockManagement = config("tables.table_names.stockManagement");
            $articles = config("tables.table_names.articles");
            $units = config("tables.table_names.units");

            $query->where(function ($query) use ($search, $articles) {
                $query->orWhere("$articles.code_article", "like", "%{$search}%")
                    ->orWhere("$articles.description", "like", "%{$search}%");
            });

            $procurementArticles = $query
                ->select(
                    "$articles.code_article",
                    "$articles.description",
                    "$articles.technical_specifications",
                    "$articles.price_map",
                    "$stockManagement.price_map as price",
                    "$stockManagement.stock_available as quantity_in_store",
                    "$articles.unit_measure",
                    "$units.abbreviation as abbreviation",
                    "$units.description as unit_measure_name"
                )
                ->orderBy("$articles.description")
                ->get();

            return response()->json([
                "success" => !empty($procurementArticles),
                "items" => $procurementArticles,
                "total_count" => $procurementArticles->count()
            ]);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                "success" => false,
                "items" => [],
                "total_count" => 0,
                "message" => ErrorMessages::getMessage("err_0005")
            ]);
        }
    }

    public function getArticlesByType(Request $request): JsonResponse
    {
        try {

            if (empty($request->get("type_article"))) {
                return response()->json([
                    "success" => false,
                    "items" => [],
                    "total_count" => 0
                ]);
            }

            $query = $this->getArticlesQueryBuilder($request);

            $stockManagement = config("tables.table_names.stockManagement");
            $articles = config("tables.table_names.articles");
            $units = config("tables.table_names.units");

            $procurementArticles = $query
                ->select(
                    "$articles.code_article",
                    "$articles.description",
                    "$articles.technical_specifications",
                    "$articles.price_map",
                    "$stockManagement.price_map as price",
                    "$stockManagement.stock_available as quantity_in_store",
                    "$articles.unit_measure",
                    "$units.abbreviation as abbreviation",
                    "$units.description as unit_measure_name"
                )
                ->orderBy("$articles.description")
                ->get();

            return response()->json([
                "success" => !empty($procurementArticles),
                "items" => $procurementArticles,
                "total_count" => $procurementArticles->count()
            ]);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                "success" => false,
                "items" => [],
                "total_count" => 0,
                "message" => ErrorMessages::getMessage("err_0005")
            ]);
        }
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
            ) = $this->getFullJobCardDetails($reference);

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
            ) = $this->getFullJobCardDetails($reference);

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

    public function partsSelection(Request $request): View|Application|Factory
    {
        $step = '1';
        $repairTypes = [];
        $accessories_checked_in = [];
        $accessories = [];
        $details = [];
        $workshop_sections = [];
        $defects = [];
        $comments = [];

        return view('modules.workshopManagement.workOrder.create_old')->with(
            compact(
                'repairTypes',
                'accessories',
                'details',
                'accessories_checked_in',
                'step',
                'workshop_sections',
                'defects',
                'comments'
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
            ) = $this->getFullJobCardDetails($request->get("reference"));

        $taskHeader = null;
        $approvalHistory = [];
        if ($request->get("reference")) {
            $taskHeader = WorkflowTaskHeader::where('reference', '=', $request->get("reference"))->first();
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
                    'observation'
                )
            );
    }

    public function getFuelLevels(): JsonResponse
    {
        return response()->json(
            [
                "state" => "success",
                "payload" => GeneralTable::where(Constants::TYPE_KEY, ConfigurationTypes::FUEL_LEVELS->value)
                    ->get()
            ]
        );
    }

    public function saveJobCardHeader(JobCardRequest $request): JsonResponse
    {
        try {
            $response = $this->workshopService->createJobCard($request);
            return response()->json(
                [
                    "success" => true,
                    "payload" => $response,
                    "redirectUrl" => URL::signedRoute("vehicle.workshop.checkin", [
                        "step" => 2,
                        "reference" => $response->job_card_no
                    ]),
                ]
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    "success" => false,
                    "payload" => [],
                    "message" => ErrorMessages::getMessage("err_0005")
                ]
            );
        }
    }

    public function closeJobCard(WorkOrderClosure $request): JsonResponse
    {
        try {
            return $this->workshopService->workOrderClosure($request);
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
                [
                    "success" => false,
                    "payload" => [],
                    "message" => $message
                ]
            );
        }
    }

    public function openJobCardClosure(Request $request): View
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
            ) = $this->getFullJobCardDetails($jobCardClosureReferenceNumber);

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
    }

    public function saveJobCardAccessories(Request $request): JsonResponse
    {
        try {
            $this->workshopService->createJobCardAccessories($request);
            return response()->json([
                "success" => true,
                "message" => SystemMessages::accessoriesCheckedIn(),
                "redirectUrl" =>
                    URL::signedRoute("vehicle.workshop.checkin",
                        ["step" => 3, "reference" => $request->get("job_card_voucher")]),
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    "success" => false,
                    "payload" => [],
                    "message" => ErrorMessages::getMessage("err_0005")
                ]
            );
        }
    }

    public function saveJobCardDefects(VehicleDefectsRequest $request): JsonResponse
    {
        try {
            $this->workshopService->createJobCardDefects($request);
            return response()->json([
                "success" => true,
                "message" => SystemMessages::defectRecorded(),
                "redirectUrl" => URL::signedRoute("show.job.card",
                    ["step" => 4, "reference" => $request->get("job_card_no")]),
            ]);
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
                [
                    "success" => false,
                    "payload" => [],
                    "message" => $message
                ]
            );
        }
    }

    public function saveJobCardMaterialRequisition(WorkshopRequisitionRequest $request): JsonResponse
    {
        try {
            return $this->workshopRequisitionService->processJobCardMaterialRequisition($request);
        } catch (\Exception $e) {
            $message = ErrorMessages::getMessage("err_0005");
            if ($e instanceof MaterialReservationException
                || $e instanceof WorkflowTaskCreationFailedException
                || $e instanceof VehicleStateException) {
                $message = $e->getMessage();
                Log::info($e);
            } else {
                Log::error($e);
            }
            return response()->json([
                "success" => false,
                "message" => $message
            ]);
        }
    }

    public function saveWorkShopMaterialReservation(WorkshopMaterialResevationRequest $request): JsonResponse
    {
        try {
            return $this->workshopRequisitionService->processMaterialReservation($request);
        } catch (\Exception $e) {

            $message = ErrorMessages::getMessage("err_0005");
            if ($e instanceof MaterialReservationException
                || $e instanceof WorkflowTaskCreationFailedException
                || $e instanceof VehicleStateException) {
                $message = $e->getMessage();
            } else {
                Log::error($e);
            }
            return response()->json([
                "success" => false,
                "message" => $message
            ]);
        }
    }

    public function saveJobCardServiceRequest(WorkshopServiceRequisitionRequest $request): JsonResponse
    {
        try {
            return $this->workshopRequisitionService->processJobCardServiceRequest($request);
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage("err_0005");

            if ($e instanceof MaterialReservationException
                || $e instanceof WorkflowTaskCreationFailedException
                || $e instanceof VehicleStateException) {
                $message = $e->getMessage();
            }
            return response()->json([
                "success" => false,
                "message" => $message
            ]);
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
                [
                    "success" => false,
                    "payload" => [],
                    "message" => $message
                ]
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
                [
                    "success" => false,
                    "payload" => [],
                    "message" => $message
                ]
            );
        }
    }

    public function saveWorkShopServicesReservation(WorkshopServiceReservationRequest $request): JsonResponse
    {
        try {
            return $this->workshopRequisitionService->processServiceReservation($request);
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage("err_0005");

            if ($e instanceof MaterialReservationException
                || $e instanceof WorkflowTaskCreationFailedException
                || $e instanceof VehicleStateException) {
                $message = $e->getMessage();
            }
            return response()->json([
                "success" => false,
                "message" => $message
            ]);
        }
    }

    /**
     * @param $reference
     * @param $step
     * @return array
     */
    public function getJobCardCreationData($reference, $step): array
    {
        list($repairTypes, $accessories, $workshop_sections) = $this->getWorkshopsRepairTypesAndSections();
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

    private function getFullJobCardDetails($reference): array
    {
        list($repairTypes, $accessories, $workshop_sections) = $this->getWorkshopsRepairTypesAndSections();

        $accessoriesCheckedIn = null;
        $details = null;
        $defects = collect([]);
        $comments = collect([]);
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
                $observation) = $this->getFullJobCardData($reference);
        }

        return array(
            $repairTypes,
            $accessoriesCheckedIn,
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

    public function deleteRecord(Request $request): JsonResponse
    {
        try {

            $entry = WorkShopVehicleDefect::where("id", "=", $request->record_id)
                ->first();

            if (empty($entry)) {
                return response()->json([
                    "success" => false,
                    "message" => self::RECORD_NOT_FOUND,
                ]);
            }

            $entry->deleted_at = Carbon::now();
            $entry->save();
            return response()->json([
                "success" => true,
                "message" => self::RECORD_REMOVED_SUCCESSFULLY,
            ]);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json([
                "success" => false,
                "message" => ErrorMessages::getMessage('err_0005')
            ]);
        }
    }

    public function eSign(Request $request): JsonResponse
    {
        try {
            $staffNumber = $request->get('loginId');

            $driver = PHCMSEmployee::where('con_st_code', '=', 'ACT')
                ->where(function ($query) use ($staffNumber) {
                    $query->where('alt_per_no', '=', $staffNumber)
                        ->orWhere('con_per_no', '=', $staffNumber);
                })
                ->first();

            $driverStaffNo = $driver->alt_per_no;

            if (empty($driver)) {
                return response()->json([
                    "success" => false,
                    "message" => "Assessment Signatory is not a driver",
                ]);
            }

            $entry = JobCardHeader::where("job_card_no", "=", $request->get('reference'))
                ->first();

            if (empty($entry)) {
                throw new
                InvalidAssessmentSignatoryException(self::RECORD_NOT_FOUND);
            }

            if ((($driverStaffNo != $staffNumber)
                    || ($entry->driver_in != $staffNumber))
                || ($driverStaffNo !== $entry->driver_in)) {
                throw new
                InvalidAssessmentSignatoryException(
                    "Assessment Signatory is not the driver who brought the vehicle"
                );
            }

            if ($driver instanceof Driver
                && Hash::check($request->get('password'),
                    $driver->password)) {
                Log::info('Commence Actual eSignature Authentication');
            }

            Log::info('Username ' . $request->loginId);
            Log::info('Password ' . $request->password);

            if ($driver) {
                Log::info('eSignature Successful');
                $entry->updated_at = Carbon::now();
                $entry->driver_acknowledged = 'Y';
                $entry->date_acknowledged = Carbon::now();
                $entry->save();
            } else {
                Log::info('eSignature Failed');
                throw new
                InvalidAssessmentSignatoryException("Invalid Credentials");
            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $message = ErrorMessages::getMessage('err_0005');

            if ($exception instanceof InvalidAssessmentSignatoryException) {
                $message = $exception->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

        return response()->json([
            'payload' => [],
            "success" => true,
            "message" => "Assessment Signed Successfully",
        ]);
    }


    public function deleteServiceRecord(Request $request): JsonResponse
    {
        try {
            $entry = MaterialDetail::where("id", "=", $request->get('record_id'))
                ->first();

            if (empty($entry)) {
                return response()->json([
                    "success" => false,
                    "message" => self::RECORD_NOT_FOUND,
                ]);
            }

            $entry->deleted_at = Carbon::now();
            $entry->save();
            return response()->json([
                "success" => true,
                "message" => self::RECORD_REMOVED_SUCCESSFULLY,
            ]);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json([
                "success" => false,
                "message" => ErrorMessages::getMessage('err_0005')
            ]);
        }
    }

    public function deleteMaterialRecord(Request $request): JsonResponse
    {
        try {
            $entry = MaterialDetail::where("id", "=", $request->record_id)
                ->first();

            if (empty($entry)) {
                return response()->json([
                    "success" => false,
                    "message" => self::RECORD_NOT_FOUND,
                ]);
            }

            $entry->deleted_at = Carbon::now();
            $entry->save();
            return response()->json([
                "success" => true,
                "message" => self::RECORD_REMOVED_SUCCESSFULLY,
            ]);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json([
                "success" => false,
                "message" => ErrorMessages::getMessage('err_0005')
            ]);
        }
    }

    /**
     * @param Request $request
     * @return Builder
     */
    public function getArticlesQueryBuilder(Request $request): Builder
    {
        $stockManagement = config("tables.table_names.stockManagement");
        $articles = config("tables.table_names.articles");
        $units = config("tables.table_names.units");

        $query = DB::table("$articles")
            ->leftJoin("$units",
                "$articles.unit_measure",
                "=",
                "$units.code_unit")
            ->leftJoin("$stockManagement",
                "$articles.code_article",
                "=",
                "$stockManagement.code_article");

        $itemType = $request->get("type_article");
        $storeCode = $request->get("store_code");

        if ($itemType == RequisitionItemTypes::STOCK_ITEM_CODE) {
            $query->where(function ($q) use ($storeCode, $stockManagement, $articles) {
                $q->whereIn("$articles.code_group", ["01", "04", "30"]);
                $q->where("$stockManagement.code_store", "=", $storeCode);
            });
        } elseif ($itemType == RequisitionItemTypes::NON_STOCK_ITEM_CODE) {
            $query->where(function ($q) use ($articles) {
                $q->where("$articles.code_group", "=", "40");
                $q->where("$articles.code_subgroup", "=", "07");
            });
        } elseif ($itemType == RequisitionItemTypes::SERVICE_ITEM_CODE) {
            $query->where(function ($q) use ($articles) {
                $q->where("$articles.code_group", "=", "41");
                $q->where("$articles.code_subgroup", "=", "02");
            });
        }

        $query->where("$articles.type_article", "=", $request->get("type_article"));
        return $query;
    }

    public function getStoreAndPurchaseOffice(Request $request): JsonResponse
    {
        Log::info($request->has("workshop_code"));
        try {
            if (!$request->has("workshop_code")) {
                return response()->json([
                    "state" => "failure",
                    "payload" => []
                ]);
            }

            $workshopCode = $request->get("workshop_code");
            Log::info($workshopCode);

            Log::info("Value Received " . $workshopCode);

            return response()->json([
                "state" => "success",
                "payload" => $this->workshopService->getWorkShopPurchaseOfficeAndStore($workshopCode)
            ]);
        } catch (Exception $e) {
            return response()->json([
                "state" => "failure",
                "payload" => []
            ]);
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

    /**
     * @return array
     */
    public function getWorkshopsRepairTypesAndSections(): array
    {
        $repairTypes = GeneralTable::where(Constants::TYPE_KEY, ConfigurationTypes::REPAIR_TYPE->value)
            ->where("active", "=", 1)
            ->orderBy("name")
            ->get();

        $accessories = Accessory::where("status", "=", StatusHelper::active())
            ->orderBy("name")
            ->get();

        $workshopSections = GeneralTable::where(Constants::TYPE_KEY, ConfigurationTypes::WORK_SHOP_SECTION)
            ->where("active", "=", 1)
            ->orderBy("name")
            ->get();

        return array($repairTypes, $accessories, $workshopSections);
    }

    public function getReservedMaterialAndServices(Request $request): JsonResponse
    {
        if (!$request->has('vehicleRegistration')) {
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }

        $vehicleRegistration = $request->get('vehicleRegistration');
        Log::info("Checking for reservations for $vehicleRegistration");
        $details = $this->workshopService->getReservedMaterialsAndServices($vehicleRegistration);
        return response()->json([
            'state' => 'success',
            'payload' => $details
        ]);
    }

    public function attachReservedArticlesToJobCard(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $reference = $request->get('jobCardNumber');
            $documentIds = $request->get('items');
            Log::debug("Attaching Articles on $reference");
            $requestIds = [];

            foreach ($documentIds as $documentId) {
                $requestIds[] = $documentId['requestId'];
                Log::debug("Article " . $documentId['requestId']);
            }

            $workOrder = JobCardHeader::where("job_card_no", "=", $reference)
                ->first();
            $user = Auth::user();

            $materials = MaterialDetail::whereIn('id', $requestIds)->get();
            Log::debug("Articles found :" . $materials->count());

            foreach ($materials as $material) {
                Log::debug("Attaching Article :" . $material->material_code);
                $materialHeader = MaterialHeader::where('req_no', '=', $material->req_no)->first();

                Log::debug("Item Type :" . $materialHeader->item_type);

                switch ($materialHeader->item_type) {
                    case RequisitionItemTypes::STOCK_ITEM:
                        Log::debug("Article Group:" . $materialHeader->item_type);
                        WorkShopMaterial::firstOrCreate(
                            [
                                "wshp_act_code" => $workOrder->wshp_act_code,
                                "workshop_code" => $workOrder->workshop_code,
                                "mat_code" => $material->material_code,
                            ],
                            [
                                'sch_flouted' => 'N',
                                "form_order" => $materialHeader->form_order,
                                "st_pur" => $materialHeader->st_pur,
                                "evaluation" => "Y",
                                "date_mat" => \Carbon\Carbon::now(),
                                "unit_of_measure" => $material->unit_of_measure,
                                "quantity" => $material->quantity,
                                "amount" => $material->amount,
                                "price" => $material->price,
                                "store_code" => $material->stores_code,
                                "supplier_code" => $material->supplier_code,
                                "veh_reg_no" => $material->reg_no,
                                "specifications" => $material->specifications,
                                "requested_by" => $material->created_by,
                                "status" => StatusHelper::new(),
                                "created_by" => $user->staff_no,
                            ]);
                        break;
                    case RequisitionItemTypes::SERVICE:
                    case RequisitionItemTypes::NON_STOCK_ITEM:
                        Log::debug("Article Group :" . $materialHeader->item_type);
                        WorkShopServiceModel::firstOrCreate(
                            [
                                "wshp_act_code" => $workOrder->wshp_act_code,
                                "wshp_code" => $workOrder->workshop_code,
                                "movt_no" => $materialHeader->form_order,
                                "mat_code" => $material->material_code,
                            ],
                            [
                                //"wshp_act_code" => $workOrder->wshp_act_code,
                                //"wshp_code" => $workOrder->workshop_code,
                                //"movt_no" => $materialHeader->form_order,
                                //"mat_code" => $material->material_code,
                                // "requested_by_id" => $user->id,
                                "date_send" => \Carbon\Carbon::now(),
                                "evaluation" => "Y",
                                "unit_of_measure" => $material->unit_of_measure,
                                "quantity" => $material->quantity,
                                "amount_est" => $material->amount,
                                "price" => $material->price,
                                "store_code" => $material->stores_code,
                                "code_office" => $materialHeader->purchase_office,
                                "supp_code" => $materialHeader->supplier_code,
                                "veh_reg_no" => $material->reg_no,
                                "specifications" => $material->specifications,
                                "originator" => $user->staff_no,
                                "stf_number" => $materialHeader->st_pur,
                                "status" => $materialHeader->status,
                                "created_by" => $user->id
                            ]);
                        break;
                    default:
                        break;
                }

                $material->claimed = 'Y';
                $material->save();
            }

            DB::commit();
            return response()->json([
                'payload' => [],
                'success' => true,
                'message' => SystemMessages::ARTICLES_ATTACHED_SUCCESSFULLY
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'payload' => [],
                'success' => false,
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    }

    /**
     * @param $reference
     * @return array
     */
    public function getFullJobCardData($reference): array
    {
        $accessoriesCheckedIn = WorkShopVehicleAccessory::where("job_card_no", "=", $reference)
            ->get();

        $details = $this->workshopService->getJobCardDetails($reference);

        $officeDetails = $this->workshopService->getWorkShopPurchaseOfficeAndStore($details->workshop_code);

        $vehicleSys = 'VEH_SYS';
        $defectCategory = 'WCT';
        $defects = DB::table("wm_vehicle_defects def")
            ->join("wm_workshop_tables wckt", function (JoinClause $join) use ($defectCategory) {
                $join->on("def.defect_category_code", "=", "wckt.code")
                    ->where(function ($query) use ($defectCategory) {
                        $query->where("wckt.type_code", "=", $defectCategory);
                    });
            })
            ->join("wm_workshop_tables wckta",
                function (JoinClause $join) use ($vehicleSys) {
                    $join->on("def.veh_sys", "=", "wckta.code")
                        ->where("wckta.type_code", "=", $vehicleSys);
                })
            ->where("def.workshop_reference", "=", $details->wshp_act_code)
            ->select(
                "def.id",
                "def.veh_sys",
                "def.defect_id",
                "def.date_def",
                "def.created_at",
                "wckta.description as system_name",
                "def.defect_category_code",
                "wckt.description as defect_category_name",
                "def.defect_code",
                "def.defect_name",
                "def.section_code"
            )->get();

        $comments = WorkShopComment::where("workshop_reference", "=", $details->wshp_act_code)->get();

        $materialsHeader = WorkShopMaterialHeader::where("job_card_no", "=", $reference)->first();

        $materials = $this->workshopRequisitionService
            ->getWorkShopRequisitionItems($reference, $details->wshp_act_code);

        $services = $this->workshopRequisitionService->getWorkShopRequisitionServiceItems($details->wshp_act_code);

        $nonStock = $this->workshopRequisitionService->getWorkShopRequisitionNonStockItems($details->wshp_act_code);

        $observation = AssessmentObservation::where("reference", "=", $details->wshp_act_code)->get();

        $pettyCashItems = collect([]);

        $materials = $materials->merge($nonStock);

        $labour = DB::table('wm_workshop_labours labour')
            ->where("wshp_act_code", "=", $details->wshp_act_code)
            ->join('wm_workshop_tables defect',
                'labour.defect_id',
                '=',
                'defect.id')
            ->select('labour.*', 'defect.description as defect_name')
            ->get();

        return array(
            $accessoriesCheckedIn,
            $details,
            $officeDetails,
            $defects,
            $comments,
            $materials,
            $materialsHeader,
            $services,
            $labour,
            $pettyCashItems,
            $observation);
    }

    public function saveImprestBuyItems(PettyCashItems $request): JsonResponse
    {
        try {
            Http::asForm()->post(
                config('systeminfo.petty_cash_url'),
                $request
            );
            return response()->json(
                [
                    'state' => 'success',
                    'payload' => $request->all()
                ]
            );
        } catch (Exception $e) {
            Log::error($e);
        }
    }

}
