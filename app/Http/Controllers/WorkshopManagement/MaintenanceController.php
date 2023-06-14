<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Enums\RequisitionItemTypes;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\MaterialReservationException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobCardRequest;
use App\Http\Requests\VehicleDefectsRequest;
use App\Http\Requests\WorkshopRequisitionRequest;
use App\Models\configurations\ConfigAccessories;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\MaterialDetail;
use App\Models\RequisitionTypes;
use App\Models\WorkShopManagement\VehicleDefects;
use App\Models\WorkShopManagement\WorkShopComments;
use App\Models\WorkShopManagement\WorkShopVehicleAccessories;
use App\Services\Requisitions\FuelRequisitionService;
use App\Services\Requisitions\WorkshopRequisitionService;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\WorkShopManagement\WorkshopService;
use Exception;
use Illuminate\Contracts\View\Factory;
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
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $workshopsVehicleList = $this->workshopService->getJobCardHeader();

        return view('modules.workshopManagement.vehiclesInWorkshop')
            ->with(
                compact(
                    'workshopsVehicleList'
                )
            );
    }


    public function create(Request $request): View
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        list($step, $repairTypes, $accessories_checked_in, $accessories, $details, $workshop_sections, $defects,
            $comments, $officeDetails, $materials) = $this->getJobCardCreationData($request);

        $view_name = 'modules.requisitions.maintenance.create';

        return view($view_name)
            ->with(
                compact(
                    'repairTypes',
                    'accessories',
                    'details',
                    'accessories_checked_in',
                    'step',
                    'workshop_sections',
                    'defects',
                    'comments',
                    'officeDetails',
                    'materials'
                )
            );
    }


    public function show(Request $request): \Illuminate\Contracts\View\View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $req_no = $request->get('ref');

        $user = Auth::user();

        [$header, $details] = $this->workshopRequisitionService->getWorkShopRequisitionDetail($req_no);

        $requestDetails = $header;

        if ($requestDetails == null) {
            abort(404);
        }


        $requisitionTypes = RequisitionTypes::where('status', '01')->where('module', 'FR')->get();

        $daysToNextRefuel = config('settings.fuel_requisition_validity');

        $approvalHistory = [];

        return view('modules.requisitions.maintenance.show')
            ->with(compact(
                'user',
                'requisitionTypes',
                'requestDetails',
                'details',
                'daysToNextRefuel',
                'approvalHistory'
            ));
    }

    public function searchArticle(Request $request): JsonResponse
    {
        try {

            if (empty($request->get('type_article'))) {
                return response()->json([
                    'success' => false,
                    'items' => [],
                    'total_count' => 0
                ]);
            }

            $search = trim(strtoupper($request->get('search')));

            // SPMS ARTICLES VIEW
            $query = DB::table('spms_articles_view')
                ->leftJoin('units_view',
                    'spms_articles_view.unit_measure',
                    '=',
                    'units_view.code_unit')
                ->leftJoin('stock_management_view',
                    'spms_articles_view.code_article',
                    '=',
                    'stock_management_view.code_article');

            $itemType = $request->get('type_article');
            $store_code = $request->get('store_code');

            if ($itemType == RequisitionItemTypes::StockItemCode) {
                $query->where(function ($q) use ($itemType, $store_code) {
                    $q->whereIn('spms_articles_view.code_group',
                        ['01', '04', '30']);
                    $q->where('stock_management_view.code_store', '=', $store_code);
                });
            } else if ($itemType == RequisitionItemTypes::NonStockItemCode) {
                $query->where(function ($q) use ($itemType) {
                    $q->where('spms_articles_view.code_group', '=', '40');
                });
            } else if ($itemType == RequisitionItemTypes::ServiceItemCode) {
                $query->where(function ($q) use ($itemType) {
                    $q->where('spms_articles_view.code_group', '=', '41');
                });
            }

            $query->where('spms_articles_view.type_article', '=', $request->get('type_article'));
                //->where('stock_management_view.level_type', '=', '02');

            $query->where(function ($query) use ($search) {
                $query->orWhere('spms_articles_view.code_article', 'like', "%{$search}%")
                    ->orWhere('spms_articles_view.description', 'like', "%{$search}%");
            });

            $procurementArticles = $query
                ->select(
                    'spms_articles_view.code_article',
                    'spms_articles_view.description',
                    'spms_articles_view.technical_specifications',
                    'spms_articles_view.price_map',
                    'stock_management_view.price_map as price',
                    'stock_management_view.stock_available as quantity_in_store',
                    'spms_articles_view.unit_measure',
                    'units_view.abbreviation as abbreviation',
                    'units_view.description as unit_measure_name'
                )->get();

            return response()->json([
                'success' => !empty($procurementArticles),
                'items' => $procurementArticles,
                'total_count' => $procurementArticles->count()
            ]);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'items' => [],
                'total_count' => 0,
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    }

    public function accessoriesTab(Request $request): View
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        if (!$request->has('step')) {
            return redirect(URL::signedRoute('maintenance.requisition', ['step' => 1]));
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
            $materials
            ) = $this->getJobCardCreationData($request);

        return view('modules.requisitions.maintenance.create')
            ->with(
                compact(
                    'repairTypes',
                    'accessories',
                    'details',
                    'accessories_checked_in',
                    'step',
                    'workshop_sections',
                    'defects',
                    'comments',
                    'officeDetails',
                    'materials'
                )
            );
    }

    public function defectsTab(Request $request): Application|\Illuminate\Contracts\View\View|Factory|Redirector|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        list(
            $step,
            $repairTypes,
            $accessories_checked_in,
            $accessories,
            $details,
            $workshop_sections,
            $defects,
            $comments, $officeDetails, $materials) = $this->getJobCardCreationData($request);

        return view('modules.requisitions.maintenance.create')
            ->with(
                compact(
                    'repairTypes',
                    'accessories',
                    'details',
                    'accessories_checked_in',
                    'step',
                    'workshop_sections',
                    'defects',
                    'comments',
                    'officeDetails',
                    'materials'
                )
            );
    }


    public function getFuelLevels(): JsonResponse
    {
        $fuel_levels = GeneralTableConfigurations::where(Constants::TYPE_KEY, ConfigurationTypes::FUEL_LEVELS->value)
            ->get();

        return response()->json(
            [
                'state' => 'success',
                'payload' => $fuel_levels
            ]
        );
    }

    public function processJobCard(JobCardRequest $request): JsonResponse
    {
        try {
            $response = $this->workshopService->createJobCard($request);
            return response()->json(
                [
                    'success' => true,
                    'payload' => $response,
                    'redirectUrl' => URL::signedRoute('accessories.job.card', ['step' => 2, 'reference' => $response->job_card_no]),
                ]
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    'success' => false,
                    'payload' => [],
                    'message' => ErrorMessages::getMessage('err_0005')
                ]
            );
        }
    }

    public function processJobCardAccessories(Request $request): JsonResponse
    {
        try {
            $this->workshopService->createJobCardAccessories($request);
            return response()->json([
                'success' => true,
                'message' => SystemMessages::accessoriesCheckedIn(),
                'redirectUrl' => URL::signedRoute('defects.job.card',
                    ['step' => 3, 'reference' => $request->get('job_card_voucher')]),
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    'success' => false,
                    'payload' => [],
                    'message' => ErrorMessages::getMessage('err_0005')
                ]
            );
        }
    }

    public function processJobCardDefects(VehicleDefectsRequest $request): JsonResponse
    {
        try {
            $this->workshopService->createJobCardDefects($request);
            return response()->json([
                'success' => true,
                'message' => SystemMessages::defectRecorded(),
                'redirectUrl' => URL::signedRoute('defects.job.card',
                    ['step' => 4, 'reference' => $request->get('job_card_no')]),
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    'success' => false,
                    'payload' => [],
                    'message' => ErrorMessages::getMessage('err_0005')
                ]
            );
        }
    }


    public function processWorkShopMaterials(WorkshopRequisitionRequest $request): JsonResponse
    {
        try {
           return $this->workshopRequisitionService->processRequest($request);
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');

            if ($e instanceof MaterialReservationException || $e instanceof WorkflowTaskCreationFailedException) {
                $message = $e->getMessage();
            }
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getJobCardCreationData(Request $request): array
    {
        $step = $request->get('step') ?? 0;
        $reference = $request->get('reference');

        $repairTypes = GeneralTableConfigurations::where(Constants::TYPE_KEY, ConfigurationTypes::REPAIR_TYPE->value)
            ->where('active', '=', 1)
            ->get();

        $accessories = ConfigAccessories::where('status', '=', StatusHelper::active())->get();

        $workshop_sections = GeneralTableConfigurations::where(Constants::TYPE_KEY, ConfigurationTypes::WORK_SHOP_SECTION)
            ->where('active', '=', 1)
            ->get();

        $accessories_checked_in = null;
        $details = null;
        $defects = collect([]);
        $comments = [];
        $officeDetails = null;
        $materials = collect([]);

        if ($reference) {
            $accessories_checked_in = WorkShopVehicleAccessories::where('job_card_no', '=', $reference)
                ->get();
            $details = $this->workshopService->getJobCardDetails($reference);

            $officeDetails = $this->workshopService->getWorkShopPurchaseOfficeAndStore($details->workshop_code);

            $defects = VehicleDefects::where('workshop_reference', '=', $details->workshop_doc_no)->get();

            $comments = WorkShopComments::where('workshop_reference', '=', $details->workshop_doc_no)->get();

            $materials = $this->workshopRequisitionService->getWorkShopRequisitionItems($reference);
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
            $materials
        );
    }


    public function deleteRecord(Request $request): JsonResponse
    {
        try {

            $entry = VehicleDefects::where('id', '=', $request->record_id)
                ->first();

            if (empty($entry)) {
                return response()->json([
                    'success' => false,
                    'message' => "Record Not Found",
                ]);
            }

            $entry->deleted_at = Carbon::now();
            $entry->save();
            return response()->json([
                'success' => true,
                'message' => "Record Removed Successfully",
            ]);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => "We could not complete processing your request to an error",
            ]);
        }
    }


    public function deleteMaterialRecord(Request $request): JsonResponse
    {
        try {
            $entry = MaterialDetail::where('id', '=', $request->record_id)
                ->first();

            if (empty($entry)) {
                return response()->json([
                    'success' => false,
                    'message' => "Record Not Found",
                ]);
            }

            $entry->deleted_at = Carbon::now();
            $entry->save();
            return response()->json([
                'success' => true,
                'message' => "Record Removed Successfully",
            ]);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => "We could not complete processing your request to an error",
            ]);
        }
    }
}
