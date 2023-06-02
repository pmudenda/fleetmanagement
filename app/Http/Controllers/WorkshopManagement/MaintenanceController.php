<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\configurations\ConfigAccessories;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\RepairTypes;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\WorkShopManagement\WorkshopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        $repairTypes = GeneralTableConfigurations::where(Constants::TYPE_KEY, ConfigurationTypes::REPAIR_TYPE->value)->get();

        $accessories = ConfigAccessories::where('status', '=', StatusHelper::active())->get();

        return view('modules.requisitions.maintenance.create')
            ->with(
                compact(
                    'repairTypes',
                    'accessories'
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

    public function processJobCard(Request $request): JsonResponse
    {
        $response = [];
        if ($request->get('modelName') == 'JobCardHeader') {
            $response = $this->workshopService->createJobCard($request);
        }

        return response()->json(
            [
                'state' => 'success',
                'payload' => $response
            ]
        );
    }
}
