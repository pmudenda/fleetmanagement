<?php

namespace App\Http\Controllers;

use App\Models\DataCleanUp;
use App\Models\Reference\TMSDataCleanUp;
use App\Models\Reports\FuelCost;
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
        /*$month = 60 * 60 * 24 * 30;
        $data = cache()->remember('fuel_cost', $month, function () {
        });*/

        $year = $request->get('year') ?? Carbon::now()->year;
        $cost_by_year = DB::table('zfm_fuel_cost')
            ->select(DB::raw('SUM(ttl) as cost, year, fuel_type'))
            ->groupBy('year','fuel_type')
            ->get();

        $data = []; /*FuelCost::get()
            ->where('year', '=', '2023')
            ->where('month', '=', '04')
            ->paginate(10);*/

        return response()->json([
            'state' => 'success',
            'payload' => [
                $data,
                $cost_by_year
            ]
        ]);
    }
}
