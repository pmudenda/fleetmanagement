<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Enums\RequisitionItemTypes;
use App\Exceptions\MaterialReservationException;
use App\Exceptions\VehicleStateException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobCardRequest;
use App\Http\Requests\VehicleDefectsRequest;
use App\Http\Requests\WorkOrderClosure;
use App\Http\Requests\WorkshopMaterialResevationRequest;
use App\Http\Requests\WorkshopRequisitionRequest;
use App\Http\Requests\WorkshopServiceRequisitionRequest;
use App\Http\Requests\WorkshopServiceReservationRequest;
use App\Models\MaterialDetail;
use App\Models\Reference\PHCMSEmployee;
use App\Models\RequisitionType;
use App\Models\Settings\Accessory;
use App\Models\Settings\GeneralTableConfiguration;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Models\WorkshopLabour;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Models\WorkShopManagement\VehicleDefect;
use App\Models\WorkShopManagement\WorkShopComment;
use App\Models\WorkShopManagement\WorkShopMaterialHeader;
use App\Models\WorkShopManagement\WorkShopServiceModel;
use App\Models\WorkShopManagement\WorkShopVehicleAccessory;
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
            //abort(401);
        }

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
            ) = $this->getJobCardCreationData($request);

        $view_name = "modules.workshopManagement.workOrder.create";
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

    public function start(Request $request): \Illuminate\Contracts\View\View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $step = '1';
        $repairTypes = GeneralTableConfiguration::where(Constants::TYPE_KEY, ConfigurationTypes::REPAIR_TYPE->value)
            ->where("active", "=", 1)
            ->orderBy("name")
            ->get();
        $accessories_checked_in = [];
        $accessories = [];
        $details = [];
        $workshop_sections = [];
        $defects = [];
        $comments = [];

        $view_name = 'modules.workshopManagement.workOrder.start';

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

    public function showJobCard(Request $request): View
    {
        $this->verifyRequestSignature($request);

        if (!$request->has("step")) {
            return redirect(URL::signedRoute("workOrder.requisition", ["step" => 1]));
        }

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
            ) = $this->getJobCardCreationData($request);

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
            return redirect(URL::signedRoute("workOrder.requisition", ["step" => 1]));
        }

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
            ) = $this->getJobCardCreationData($request);

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

    public function partsSelection(Request $request)
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
            $services, $labour
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
            ) = $this->getJobCardCreationData($request);

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
                    "redirectUrl" => URL::signedRoute("accessories.job.card", ["step" => 2, "reference" => $response->job_card_no]),
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

    public function processWorkOrderClosure(WorkOrderClosure $request): JsonResponse
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

    public function approveWorkOrderClosure(Request $request): \Illuminate\Contracts\View\View|Application|Factory|\Illuminate\Contracts\Foundation\Application
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
                "redirectUrl" => URL::signedRoute("accessories.job.card",
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

    public function processJobCardDefects(VehicleDefectsRequest $request): JsonResponse
    {
        try {
            $this->workshopService->createJobCardDefects($request);
            return response()->json([
                "success" => true,
                "message" => SystemMessages::defectRecorded(),
                "redirectUrl" => URL::signedRoute("defects.job.card",
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
     * @param Request $request
     * @return array
     */
    public function getJobCardCreationData(Request $request): array
    {
        $step = $request->get("step") ?? 0;
        $reference = $request->get("reference");

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

            // $defects = VehicleDefect::where("workshop_reference", "=", $details->workshop_doc_no)->get();
            $defects = VehicleDefect::where("workshop_reference", "=", $details->wshp_act_code)->get();

            //$defectCodes = $defects->pluck('defect_code');

            // $comments = WorkShopComment::where("workshop_reference", "=", $details->workshop_doc_no)->get();
            $comments = WorkShopComment::where("workshop_reference", "=", $details->wshp_act_code)->get();

            $materials = $this->workshopRequisitionService->getWorkShopRequisitionItems($reference);

            $materialsHeader = WorkShopMaterialHeader::where("job_card_no", "=", $reference)->first();

            // $services = WorkShopServiceModel::where("workshop_reference", "=", $details->workshop_doc_no)->get();
            $services = WorkShopServiceModel::where("wshp_act_code", "=", $details->wshp_act_code)->get();
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


    public function deleteRecord(Request $request): JsonResponse
    {
        try {

            $entry = VehicleDefect::where("id", "=", $request->record_id)
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
        /*reference: ZFMJBC0000000181
            loginId: 71581
            password: dfGctaL777TxCQF
            acceptance: on
        */
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
            Log::info(var_dump($credentials));
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

            /*return response()->json([
                'payload' => $request->all(),
                "success" => false,
                "message" => 'Opps! You have entered invalid credentials',
            ]);*/

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

            $defects = VehicleDefect::where("workshop_reference", "=", $details->wshp_act_code)->get();

            $comments = WorkShopComment::where("workshop_reference", "=", $details->wshp_act_code)->get();

            $materials = $this->workshopRequisitionService->getWorkShopRequisitionItems($reference);

            $materialsHeader = WorkShopMaterialHeader::where("job_card_no", "=", $reference)->first();

            $services = WorkShopServiceModel::where("wshp_act_code", "=", $details->wshp_act_code)->get();

            $labour = WorkshopLabour::where("wshp_act_code", "=", $details->wshp_act_code)->get();
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
