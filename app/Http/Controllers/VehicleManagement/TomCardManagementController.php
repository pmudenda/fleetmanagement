<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class TomCardManagementController extends Controller
{
    public function create(): View
    {
        return view('modules.vehicleManagement.tomcard.create');
    }
}
