<?php

namespace App\Http\Controllers;

use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\DataCleanUp;
use App\Models\Reference\TMSDataCleanUp;
use App\Services\VehicleManagement\VehicleDetailsService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    private VehicleDetailsService $vehicleDetailsService;

    public function __construct(VehicleDetailsService $vehicleDetailsService,)
    {
        $this->vehicleDetailsService = $vehicleDetailsService;
    }

    public function vehicleByStatus(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $vehicleData = $this->vehicleDetailsService->getAllVehicles();
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
        $query = DB::table('zfm_fuel_cost');

        $costByYear = $query
            ->select(DB::raw('SUM(ttl) as cost, year, fuel_type'))
            ->groupBy('year', 'fuel_type')
            ->orderBy('year')
            ->get();

        $costByUnit = $query
            ->select(DB::raw('SUM(ttl) as cost, year, fuel_req_unit'))
            ->groupBy('year', 'fuel_req_unit')
            ->orderBy('year')
            ->get();

        $costByType = $query
            ->select(DB::raw('SUM(ttl) as cost, fuel_type'))
            ->groupBy('year', 'fuel_type')
            ->orderBy('year')
            ->get();

        return response()->json(
            FleetMasterJsonResponse::response(
                'success',
                true,
                null,
                [
                    'data' => [],
                    'costByYear' => $costByYear,
                    'costByUnit' => $costByUnit,
                    'costByType' => $costByType,
                ]
            )
        );
    }
}
