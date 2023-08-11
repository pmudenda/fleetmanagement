<?php

namespace App\Http\Controllers;

use App\Models\DataCleanUp;
use App\Models\Reference\TMSDataCleanUp;
use App\Models\Reports\FuelCost;
use App\Services\VehicleManagement\VehicleDetailsService;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
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

    public function getFuelCost(Request $request): JsonResponse
    {
        /*$month = 60 * 60 * 24 * 30;
        $data = cache()->remember('fuel_cost', $month, function () {

        });*/
        $year = $request . get('year') ?? Carbon::now()->year;

        $data = FuelCost::get()
            ->where('year', '=', Carbon::now()->year)
            ->paginate(100);;

        return response()->json([
            'state' => 'success',
            'payload' => $data
        ]);
    }
}
