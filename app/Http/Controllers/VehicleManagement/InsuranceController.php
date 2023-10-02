<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class InsuranceController extends Controller
{
    public function create(): View
    {
        return view('modules.vehicleManagement.insurance.create');
    }
}
