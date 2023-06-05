<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobCardRequest;
use App\Models\configurations\ConfigAccessories;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Models\WorkShopManagement\WorkShopVehicleAccessories;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\WorkShopManagement\WorkshopService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    private WorkshopService $workshopService;
    private DocumentNumberGenerationService $numberGeneratorService;

    public function __construct(WorkshopService $workshopService, DocumentNumberGenerationService $numberGeneratorService)
    {
        $this->workshopService = $workshopService;
        $this->numberGeneratorService = $numberGeneratorService;
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

        /*$repairTypes = GeneralTableConfigurations::where(Constants::TYPE_KEY, ConfigurationTypes::REPAIR_TYPE->value)
            ->get();
        $details = null;*/

        list($step, $repairTypes, $accessories_checked_in, $accessories, $details, $workshop_sections) = $this->jobCardCreationData($request);

        return view('modules.requisitions.maintenance.create')
            ->with(
                compact(
                    'repairTypes',
                    'accessories',
                    'details',
                    'accessories_checked_in',
                    'step',
                    'workshop_sections'
                )
            );
    }

    public function accessoriesTab(Request $request): View
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        if (!$request->has('step')) {
            return redirect(URL::signedRoute('maintenance.requisition', ['step' => 1]));
        }

        list($step, $repairTypes, $accessories_checked_in, $accessories, $details, $workshop_sections) = $this->jobCardCreationData($request);

        return view('modules.requisitions.maintenance.create')
            ->with(
                compact(
                    'repairTypes',
                    'accessories',
                    'details',
                    'accessories_checked_in',
                    'step',
                    'workshop_sections'
                )
            );
    }

    public function defectsTab(Request $request): Application|\Illuminate\Contracts\View\View|Factory|Redirector|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        /*if (!$request->has('step')) {
            return redirect(URL::signedRoute('maintenance.requisition', ['step' => 1]));
        }*/

        list($step, $repairTypes, $accessories_checked_in, $accessories, $details, $workshop_sections) = $this->jobCardCreationData($request);

        return view('modules.requisitions.maintenance.create')
            ->with(
                compact(
                    'repairTypes',
                    'accessories',
                    'details',
                    'accessories_checked_in',
                    'step',
                    'workshop_sections'
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
            //if ($request->get('modelName') == 'JobCardHeader') {
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

    public function processJobCardDefects(Request $request): JsonResponse
    {
        try {
            $this->workshopService->createJobCardDefects($request);
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

    /**
     * @param Request $request
     * @return array
     */
    public function jobCardCreationData(Request $request): array
    {
        $step = $request->get('step') ?? 0;
        $reference = $request->get('reference');

        $repairTypes = GeneralTableConfigurations::where(Constants::TYPE_KEY, ConfigurationTypes::REPAIR_TYPE->value)->get();
        $accessories = ConfigAccessories::where('status', '=', StatusHelper::active())->get();
        $workshop_sections = GeneralTableConfigurations::where(Constants::TYPE_KEY, ConfigurationTypes::WORK_SHOP_SECTION)->get();

        $accessories_checked_in = null;
        $details = null;

        if($reference) {
            $accessories_checked_in = WorkShopVehicleAccessories::where('job_card_no', '=', $reference)->get();
            $details = $this->workshopService->getJobCardDetails($reference);
        }

        return array($step, $repairTypes, $accessories_checked_in, $accessories, $details, $workshop_sections);
    }
}
