<?php

namespace App\Http\Controllers;

use App\Models\Common\Directorate;
use App\Models\DataCleanUp;
use App\Models\Reference\TMSDataCleanUp;
use App\Models\Reports\FuelCost;
use App\Services\VehicleManagement\VehicleDetailsService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class ReportsController extends Controller
{
    public function vehicleByStatus(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $vehicleData = VehicleDetailsService::getAllVehicles();
        $tmsVehicleData = TMSDataCleanUp::get();
        $cleanVehicleData = DataCleanUp::get();

        return view('modules.reports.vehicleByStatus')
            ->with(compact(
                'vehicleData',
                'tmsVehicleData',
                'cleanVehicleData'));
    }

    public function fuelCost(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $data = [];
        return view('modules.reports.index')
            ->with(compact('data'));
    }

    public function getFuelCost(): JsonResponse
    {
        $month = 60 * 60 * 24 * 30;
        $data = cache()->remember('fuel_cost', $month, function () {
            return FuelCost::get();
        });
        return response()->json([
            'state' => 'success',
            'payload' => $data
        ]);
    }
}
