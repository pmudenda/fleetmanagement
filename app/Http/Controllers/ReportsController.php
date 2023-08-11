<?php

namespace App\Http\Controllers;

use App\Models\DataCleanUp;
use App\Models\Reference\TMSDataCleanUp;
use App\Models\Reports\FuelCost;
use App\Services\VehicleManagement\VehicleDetailsService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

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
        /*Route::get(, function () {}*/
        /*$data = [
            (object)[
                'year' => '2022',
                'month' => '04',
                'full_date' => '202304',
                'reg_no' => 'BAE 3795',
                'fuel_type' => 'Diesel',
                'fuel_req_unit' => 'C1931',
                'qty' => '340',
                'ttl' => '34500',
            ],
            (object)[
                'year' => '2023',
                'month' => '04',
                'full_date' => '202304',
                'reg_no' => 'BAE 3795',
                'fuel_type' => 'Petrol',
                'fuel_req_unit' => 'C1931',
                'qty' => '340',
                'ttl' => '34500',
            ],
            (object)[
                'year' => '2021',
                'month' => '05',
                'full_date' => '202304',
                'reg_no' => 'BAE 3795',
                'fuel_type' => 'Petrol',
                'fuel_req_unit' => 'C1931',
                'qty' => '340',
                'ttl' => '34500',
            ],
            (object)[
                'year' => '2021',
                'month' => '05',
                'full_date' => '202304',
                'reg_no' => 'BAE 3795',
                'fuel_type' => 'Petrol',
                'fuel_req_unit' => 'C1931',
                'qty' => '340',
                'ttl' => '34500',
            ],
            (object)[
                'year' => '2023',
                'month' => '04',
                'full_date' => '202304',
                'reg_no' => 'BAE 3795',
                'fuel_type' => 'Petrol',
                'fuel_req_unit' => 'C1931',
                'qty' => '340',
                'ttl' => '34500',
            ],
            (object)[
                'year' => '2022',
                'month' => '06',
                'full_date' => '202304',
                'reg_no' => 'BAE 3795',
                'fuel_type' => 'Diesel',
                'fuel_req_unit' => 'C1931',
                'qty' => '340',
                'ttl' => '34500',
            ],
            (object)[
                'year' => '2023',
                'month' => '03',
                'full_date' => '202304',
                'reg_no' => 'BAB 1010',
                'fuel_type' => 'Petrol',
                'fuel_req_unit' => 'C1931',
                'qty' => '340',
                'ttl' => '34500',
            ],
            (object)[
                'year' => '2021',
                'month' => '04',
                'full_date' => '202304',
                'reg_no' => 'BAB 1014',
                'fuel_type' => 'Diesel',
                'fuel_req_unit' => 'C1931',
                'qty' => '340',
                'ttl' => '34500',
            ]
        ];*/
        $data = FuelCost::get();
        return view('modules.reports.index')
            ->with(compact('data'));
    }
}
