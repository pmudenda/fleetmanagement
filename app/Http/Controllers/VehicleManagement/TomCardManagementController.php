<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class TomCardManagementController extends Controller
{
    public function create(): View
    {
        return view('modules.vehicleManagement.tomcard.create');
    }

    public function store(): JsonResponse
    {
        return response()->json();
    }

    public function list(): View
    {
        return view('modules.vehicleManagement.tomcard.create');
    }
}
