<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Constants\ErrorMessages;
use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobCardRequest;
use App\Models\configurations\ConfigAccessories;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\WorkShopManagement\WorkshopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function create(Request $request): View
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $repairTypes = GeneralTableConfigurations::where(Constants::TYPE_KEY, ConfigurationTypes::REPAIR_TYPE->value)
            ->get();
        $details = null;

        return view('modules.requisitions.maintenance.create')
            ->with(
                compact(
                    'repairTypes',
                    'details'
                )
            );
    }
    public function list(Request $request): View
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $workshopsVehicleList = JobCardHeader::get();

        return view('modules.workshopManagement.vehiclesInWorkshop')
            ->with(
                compact(
                    'workshopsVehicleList'
                )
            );
    }

    public function step2(Request $request): View
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        if ($request->has('step') && $request->get('step') != "2") {
            abort(401);
        }
        if (!$request->has('step')) {
            return redirect(URL::signedRoute('maintenance.requisition', ['step' => 1]));
        }

        $step = $request->get('step') ?? 0;
        $reference = $request->get('reference');

        $repairTypes = GeneralTableConfigurations::where(Constants::TYPE_KEY, ConfigurationTypes::REPAIR_TYPE->value)->get();

        $accessories = ConfigAccessories::where('status', '=', StatusHelper::active())->get();

        $details =  $this->workshopService->getJobCardDetails($reference);

        return view('modules.requisitions.maintenance.step2')
            ->with(
                compact(
                    'repairTypes',
                    'accessories',
                    'details'
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
                    'redirectUrl' => URL::signedRoute('continue.job.card', ['step' => 2, 'reference' => $response->job_card_no]),
                ]
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    'success' => false,
                    'payload' => [],
                    'message' => ErrorMessages::getMessage('err_005')
                ]
            );
        }
    }
}
