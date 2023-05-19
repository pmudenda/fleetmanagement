<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Enums\ConfigurationTypes;
use App\Enums\Constants;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\configurations\ConfigAccessories;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\RepairTypes;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function create(): View
    {
        $daysToNextRefuel = 0;
        $repairTypes = RepairTypes::get();
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
}
