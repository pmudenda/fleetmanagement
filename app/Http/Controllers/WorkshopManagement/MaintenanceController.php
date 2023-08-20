<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Constants\WorkflowActions;
use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Enums\RequisitionItemTypes;
use App\Enums\WorkflowProcessCodes;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\MaterialReservationException;
use App\Exceptions\VehicleStateException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleDefectsRequest;
use App\Http\Requests\WorkShopManagement\JobCardRequest;
use App\Http\Requests\WorkShopManagement\JobCardTaskAssignment;
use App\Http\Requests\WorkShopManagement\JobCardTaskReassignment;
use App\Http\Requests\WorkShopManagement\SubmitJobCardToSupervisor;
use App\Http\Requests\WorkShopManagement\WorkOrderClosure;
use App\Http\Requests\WorkShopManagement\WorkshopMaterialResevationRequest;
use App\Http\Requests\WorkShopManagement\WorkshopRequisitionRequest;
use App\Http\Requests\WorkShopManagement\WorkshopServiceRequisitionRequest;
use App\Http\Requests\WorkShopManagement\WorkshopServiceReservationRequest;
use App\Models\MaterialDetail;
use App\Models\MaterialHeader;
use App\Models\Reference\PHCMSEmployee;
use App\Models\RequisitionType;
use App\Models\Settings\Accessory;
use App\Models\Settings\GeneralTableConfiguration;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Models\WorkShopManagement\Mechanic;
use App\Models\WorkShopManagement\WorkShopComment;
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
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
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
        if (!$request->hasValidSignature()) {
            abort(401);
        }

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
            $labour
            ) = $this->getFullJobCardDetails($reference);

        $mechanics = [];
        if (!empty($details)) {
            $mechanics= Mechanic::where('status', '=', StatusHelper::active())
                ->where('workshop_code', '=', $details->workshop_code)
                ->get();
        }

        $view_name = "modules.workshopManagement.workOrder.create";

        $step = $request->get("step") ?? 0;
        return view($view_name)
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
                    'mechanics'
                )
            );
    }

    public function view(Request $request): View
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
            $labour
            ) = $this->getFullJobCardDetails($reference);

        $mechanics = [];
        if (!empty($details)) {
            $mechanics = Mechanic::where('status', '=', StatusHelper::active())
                ->where('workshop_code', '=', $details->workshop_code)
                ->get();
        }

        $view_name = "modules.workshopManagement.workOrder.view";

        return view($view_name)
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
                    'mechanics'
                )
            );
    }

    public function start(Request $request): \Illuminate\Contracts\View\View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $view_name = 'modules.workshopManagement.workOrder.start';
        $step = $request->get("step") ?? 0;
        $reference = $request->get("reference") ?? $request->get('ref');

        list(
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
            $services
            ) = $this->getJobCardCreationData($reference, $step);

        $labour = collect([]);
        return view($view_name)
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
                    'labour'
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

    public function show(Request $request): \Illuminate\Contracts\View\View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->verifyRequestSignature($request);

        $req_no = $request->get("ref");

        $user = Auth::user();

        [$header, $details] = $this->workshopRequisitionService->getWorkShopReservationDetails($req_no);

        $requestDetails = $header;

        if ($requestDetails == null) {
            abort(404);
        }

        $workflowTask = WorkflowTaskHeader::where("reference", "=", $req_no)->first();

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

    public function showJobCard(Request $request): \Illuminate\Contracts\View\View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->verifyRequestSignature($request);

        $step = $request->get("step") ?? 1;
        $reference = $request->get("reference") ?? $request->get('ref');

        list($step,
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
            $services
            ) = $this->getJobCardCreationData($reference, $step);

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
                    "services"
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

        list($step,
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
            $services
            ) = $this->getJobCardCreationData($reference, $step);

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
                    "services"
                )
            );
    }

    public function partsSelection(Request $request): \Illuminate\Contracts\View\View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {

        $step = '1';
        $repairTypes = [];
        $accessories_checked_in = [];
        $accessories = [];
        $details = [];
        $workshop_sections = [];
        $defects = [];
        $comments = [];

        $view_name = 'modules.workshopManagement.workOrder.create_old';

        return view($view_name)->with(
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
            $labour
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
                    'approvalHistory'
                )
            );
    }

    public function defectsTab(Request $request): Application|\Illuminate\Contracts\View\View|Factory|Redirector|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        $this->verifyRequestSignature($request);
        $step = $request->get("step") ?? 0;
        $reference = $request->get("reference") ?? $request->get('ref');

        list(
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
            $services
            ) = $this->getJobCardCreationData($reference, $step);

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
                    "services"
                )
            );
    }

    public function getFuelLevels(): JsonResponse
    {
        $fuel_levels = GeneralTableConfiguration::where(Constants::TYPE_KEY, ConfigurationTypes::FUEL_LEVELS->value)
            ->get();

        return response()->json(
            [
                "state" => "success",
                "payload" => $fuel_levels
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
                    "redirectUrl" => URL::signedRoute("vehicle.workshop.checkin", ["step" => 2, "reference" => $response->job_card_no]),
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

    public function closeApproveJobCard(Request $request): JsonResponse
    {
        try {

            $reference = $request->get('reference');

            //$requisitionDetail = $this->workshopRequisitionService->getReservationDetail($reference);

            DB::beginTransaction();

            /*;
            switch ($requisitionDetail->item_type) {
                case RequisitionItemTypes::Service:
                case RequisitionItemTypes::NonStockItem:
                    $process_code = WorkflowProcessCodes::PurchaseProcess->value;
                    break;
                case RequisitionItemTypes::StockItem:
                    $process_code = WorkflowProcessCodes::StoresRequisition->value;
                    break;
                default:
                    break;
            }*/

            $actionTaken = '';
            $process_code = WorkflowProcessCodes::WorkOrderClosure->value;
            $message = '';
            $action = 0;
            switch (strtolower(trim($request->get('Approved')))) {
                case 'approve':
                    $action = WorkflowActions::approve();
                    $actionTaken = "Approved";
                    $message = 'Request Approved Successfully.';
                    break;
                case 'reject':
                    $action = WorkflowActions::reject();
                    $actionTaken = "Rejected";
                    $message = 'Request Rejected.';
                    break;
                case 'send_back':
                    $action = WorkflowActions::sendBack();
                    $actionTaken = "SendBack";
                    $message = 'Request Sent Back To Originator.';
                    break;
            }

            list($nextStepId, $nextUser) = $this->workflowService->invokeWorkFlow(
                $reference,
                $process_code,
                $action,
                $actionTaken,
                $request->get('Comments')
            );

            $user = Auth::user();
            if ($nextStepId == 100 && $action == WorkflowActions::approve()) {
                $workOrder = JobCardHeader::where("job_card_no", "=", str_replace('-C', '', $reference))
                    ->first();

                $workOrder->modified_by = $user->staff_no;
                $workOrder->status = StatusHelper::authorised();
                $workOrder->updated_at = Carbon::now();
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

            } else if ($nextStepId == 100 && $action == WorkflowActions::reject()) {
                $message = 'Request Rejected';
                $status = StatusHelper::rejected();
                JobCardHeader::where("job_card_no", "=", str_replace('-C', '', $reference))
                    ->update([
                        'modified_by' => $user->staff_no,
                        'status' => $status,
                        'updated_at' => Carbon::now()
                    ]);
            } else {
                if (strtolower(trim($request->get('Approved'))) == 'approve') {
                    $message = 'Request Approved and Submitted to the Next Authority For Approval';
                    $status = StatusHelper::partiallyAuthorised();
                    JobCardHeader::where("job_card_no", "=", str_replace('-C', '', $reference))
                        ->update([
                            'modified_by' => $user->staff_no,
                            'status' => $status,
                            'updated_at' => Carbon::now()
                        ]);
                }
            }

            DB::commit();
            return response()->json([
                'requestPayload' => $request->all(),
                'success' => true,
                'redirectUrl' => route('home'),
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');
            if ($e instanceof FuelRequisitionException || $e instanceof WorkflowTaskCreationFailedException) {
                $message = $e->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }

    public function closeJobCard(WorkOrderClosure $request): JsonResponse
    {
        try {
            return $this->workshopService->workOrderClosure($request);
        } catch (\Exception $e) {
            $message = ErrorMessages::getMessage("err_0005");
            if ($e instanceof MaterialReservationException || $e instanceof WorkflowTaskCreationFailedException || $e instanceof VehicleStateException) {
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

    public function openJobCardClosure(Request $request): \Illuminate\Contracts\View\View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->verifyRequestSignature($request);

        $req_no = str_replace('-C', '', $request->get('ref'));

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
            $services, $labour
            ) = $this->getFullJobCardDetails($req_no);

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
                    'approvalHistory'
                )
            );


    }

    public function processJobCardAccessories(Request $request): JsonResponse
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


    public function processWorkShopMaterials(WorkshopRequisitionRequest $request): JsonResponse
    {
        try {
            return $this->workshopRequisitionService->processJobCardMaterialReservation($request);
        } catch (\Exception $e) {
            $message = ErrorMessages::getMessage("err_0005");
            if ($e instanceof MaterialReservationException || $e instanceof WorkflowTaskCreationFailedException || $e instanceof VehicleStateException) {
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

    public function processWorkShopMaterialReservation(WorkshopMaterialResevationRequest $request): JsonResponse
    {
        try {
            return $this->workshopRequisitionService->processMaterialReservation($request);
        } catch (\Exception $e) {

            $message = ErrorMessages::getMessage("err_0005");
            if ($e instanceof MaterialReservationException || $e instanceof WorkflowTaskCreationFailedException || $e instanceof VehicleStateException) {
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

    public function processWorkShopServices(WorkshopServiceRequisitionRequest $request): JsonResponse
    {
        try {
            return $this->workshopRequisitionService->processJobCardServiceRequest($request);
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage("err_0005");

            if ($e instanceof MaterialReservationException || $e instanceof WorkflowTaskCreationFailedException || $e instanceof VehicleStateException) {
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

    public function processWorkShopServicesReservation(WorkshopServiceReservationRequest $request): JsonResponse
    {
        try {
            return $this->workshopRequisitionService->processServiceReservation($request);
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage("err_0005");

            if ($e instanceof MaterialReservationException || $e instanceof WorkflowTaskCreationFailedException || $e instanceof VehicleStateException) {
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
        $repairTypes = GeneralTableConfiguration::where(Constants::TYPE_KEY, ConfigurationTypes::REPAIR_TYPE->value)
            ->where("active", "=", 1)
            ->orderBy("name")
            ->get();

        $accessories = Accessory::where("status", "=", StatusHelper::active())
            ->orderBy("name")
            ->get();

        $workshop_sections = GeneralTableConfiguration::where(Constants::TYPE_KEY, ConfigurationTypes::WORK_SHOP_SECTION)
            ->where("active", "=", 1)
            ->orderBy("name")
            ->get();

        $accessories_checked_in = null;
        $details = null;
        $defects = collect([]);
        $comments = [];
        $officeDetails = null;
        $materials = collect([]);
        $materialsHeader = null;
        $services = collect([]);
        $labour = collect([]);

        if ($reference) {
            $accessories_checked_in = WorkShopVehicleAccessory::where("job_card_no", "=", $reference)
                ->get();
            $details = $this->workshopService->getJobCardDetails($reference);

            $officeDetails = $this->workshopService->getWorkShopPurchaseOfficeAndStore($details->workshop_code);

            $defects = WorkShopVehicleDefect::where("workshop_reference", "=", $details->wshp_act_code)->get();

            $comments = WorkShopComment::where("workshop_reference", "=", $details->wshp_act_code)->get();

            $materials = $this->workshopRequisitionService->getWorkShopRequisitionItems($reference);

            $materialsHeader = WorkShopMaterialHeader::where("job_card_no", "=", $reference)->first();

            $services = $this->workshopRequisitionService->getWorkShopRequisitionServiceItems($details->wshp_act_code);
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
            $labour
        );
    }

    private function getFullJobCardDetails($reference): array
    {
        $repairTypes = GeneralTableConfiguration::where(Constants::TYPE_KEY, ConfigurationTypes::REPAIR_TYPE->value)
            ->where("active", "=", 1)
            ->orderBy("name")
            ->get();

        $accessories = Accessory::where("status", "=", StatusHelper::active())
            ->orderBy("name")
            ->get();

        $workshop_sections = GeneralTableConfiguration::where(Constants::TYPE_KEY, ConfigurationTypes::WORK_SHOP_SECTION)
            ->where("active", "=", 1)
            ->orderBy("name")
            ->get();

        $accessories_checked_in = null;
        $details = null;
        $defects = collect([]);
        $comments = collect([]);
        $officeDetails = null;
        $materials = collect([]);
        $materialsHeader = null;
        $services = collect([]);

        $labour = collect([]);

        if ($reference) {

            $accessories_checked_in = WorkShopVehicleAccessory::where("job_card_no", "=", $reference)
                ->get();

            $details = $this->workshopService->getJobCardDetails($reference);

            $officeDetails = $this->workshopService->getWorkShopPurchaseOfficeAndStore($details->workshop_code);

            $defects = WorkShopVehicleDefect::where("workshop_reference", "=", $details->wshp_act_code)->get();

            $comments = WorkShopComment::where("workshop_reference", "=", $details->wshp_act_code)->get();

            $materials = $this->workshopRequisitionService->getWorkShopRequisitionItems($reference);

            $materialsHeader = WorkShopMaterialHeader::where("job_card_no", "=", $reference)->first();

            $services = WorkShopServiceModel::where("wshp_act_code", "=", $details->wshp_act_code)->get();

            $labour = DB::table('wm_workshop_labours labour')
                ->where("wshp_act_code", "=", $details->wshp_act_code)
                ->join('wm_workshop_tables defect',
                    'labour.defect_id',
                    '=',
                    'defect.id')
                ->select('labour.*', 'defect.description as defect_name')
                ->get();
        }

        return array(
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
            $labour
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
                    "message" => "Record Not Found",
                ]);
            }

            $entry->deleted_at = Carbon::now();
            $entry->save();
            return response()->json([
                "success" => true,
                "message" => "Record Removed Successfully",
            ]);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json([
                "success" => false,
                "message" => "We could not complete processing your request to an error",
            ]);
        }
    }

    public function eSign(Request $request): JsonResponse
    {
        try {
            $loginId = $request->get('loginId');
            $password = $request->get('password');

            $driver = PHCMSEmployee::where('con_st_code', '=', 'ACT')
                ->where(function ($query) use ($loginId) {
                    $query->where('alt_per_no', '=', $loginId)
                        ->orWhere('con_per_no', '=', $loginId);
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
                return response()->json([
                    "success" => false,
                    "message" => "Record Not Found",
                ]);
            }

            if (($driverStaffNo != $loginId) || ($entry->driver_in != $loginId)) {
                return response()->json([
                    "success" => false,
                    "message" => "Assessment Signatory is not the driver who brought the vehicle",
                ]);
            }

            if ($driverStaffNo !== $entry->driver_in) {
                return response()->json([
                    "success" => false,
                    "message" => "Assessment Signatory is not the driver who brought the vehicle",
                ]);
            }

            $credentials = $request->only('loginId', 'password');

            // Auth::attempt($credentials)
            if ($driverStaffNo == $entry->driver_in) {

                $entry->updated_at = Carbon::now();
                $entry->driver_acknowledged = 'Y';
                $entry->date_acknowledged = Carbon::now();
                $entry->save();

                return response()->json([
                    'payload' => [],
                    "success" => true,
                    "message" => "Assessment Signed Successfully",
                ]);
            }

            return response()->json([
                'payload' => $request->all(),
                "success" => false,
                "message" => 'Opps! You have entered invalid credentials',
            ]);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json([
                "success" => false,
                "message" => "We could not complete processing your request to an error",
            ]);
        }
    }


    public function deleteServiceRecord(Request $request): JsonResponse
    {
        try {
            $entry = MaterialDetail::where("id", "=", $request->get('record_id'))
                ->first();

            if (empty($entry)) {
                return response()->json([
                    "success" => false,
                    "message" => "Record Not Found",
                ]);
            }

            $entry->deleted_at = Carbon::now();
            $entry->save();
            return response()->json([
                "success" => true,
                "message" => "Record Removed Successfully",
            ]);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json([
                "success" => false,
                "message" => "We could not complete processing your request to an error",
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
                    "message" => "Record Not Found",
                ]);
            }

            $entry->deleted_at = Carbon::now();
            $entry->save();
            return response()->json([
                "success" => true,
                "message" => "Record Removed Successfully",
            ]);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json([
                "success" => false,
                "message" => "We could not complete processing your request to an error",
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
        $store_code = $request->get("store_code");

        if ($itemType == RequisitionItemTypes::StockItemCode) {
            $query->where(function ($q) use ($itemType, $store_code, $stockManagement, $articles) {
                $q->whereIn("$articles.code_group", ["01", "04", "30"]);
                $q->where("$stockManagement.code_store", "=", $store_code);
            });
        } else if ($itemType == RequisitionItemTypes::NonStockItemCode) {
            $query->where(function ($q) use ($itemType, $articles) {
                $q->where("$articles.code_group", "=", "40");
                $q->where("$articles.code_subgroup", "=", "07");
            });
        } else if ($itemType == RequisitionItemTypes::ServiceItemCode) {
            $query->where(function ($q) use ($itemType, $articles) {
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
}
