<?php

namespace App\Http\Controllers;

use App\Models\DataCleanUp;
use App\Models\Reference\TMSDataCleanUp;
use App\Services\VehicleManagement\VehicleDetailsService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $query = DB::table('zfm_fuel_cost');

        $cost_by_year = $query
            ->select(DB::raw('SUM(ttl) as cost, year, fuel_type'))
            ->groupBy('year', 'fuel_type')
            ->get();

        $cost_by_unit = $query
            ->select(DB::raw('SUM(ttl) as cost, year, fuel_req_unit'))
            ->groupBy('year', 'fuel_req_unit')
            ->get();

        $cost_by_type = $query
            ->select(DB::raw('SUM(ttl) as cost, fuel_type'))
            ->groupBy('year', 'fuel_type')
            ->get();

        $data = [];

        return response()->json([
            'state' => 'success',
            'payload' => [
                'data' => $data,
                'costByYear' => $cost_by_year,
                'costByUnit' => $cost_by_unit,
                'costByType' => $cost_by_type,
            ]
        ]);
    }
}
