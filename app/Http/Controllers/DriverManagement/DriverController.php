<?php

namespace App\Http\Controllers\DriverManagement;

use App\Enums\ConfigurationTypes;
use App\Http\Controllers\Controller;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\Driver;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class DriverController extends Controller
{

    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $licenseClasses = GeneralTableConfigurations::where('type', '=', ConfigurationTypes::LICENSE_CLASS->value)
            ->get();

        return view('modules.driverManagement.index')
            ->with(compact('licenseClasses'));
    }

    public function driverList(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $users = []; //User::select('*')->get();
        return view('modules.driverManagement.driverList')->with(compact('users'));
    }

    public function findDriver(Request $request): JsonResponse
    {
        $drivers = Driver::where('staff_number', '=', $request->get('searchCriteria'))
            ->orWhere('name', $request->get('searchCriteria'))
            ->get();

        return response()->json([
            'success' => true,
            'payload' => $drivers
        ]);

    }
}
