<?php

namespace App\Http\Controllers;

use App\Services\VehicleManagement\VehicleDetailsService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ReportsController extends Controller
{
    public function vehicleByStatus(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $vehicleData = VehicleDetailsService::getAllVehicles();
        return view('modules.reports.vehicleByStatus')
            ->with(compact('vehicleData'));
    }
}
