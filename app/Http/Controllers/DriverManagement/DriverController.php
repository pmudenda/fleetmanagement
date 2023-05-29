<?php

namespace App\Http\Controllers\DriverManagement;

use App\Enums\ConfigurationTypes;
use App\Http\Controllers\Controller;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\Driver;
use App\Models\reference\PHCMSEmployee;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class DriverController extends Controller
{

    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $licenseClasses = GeneralTableConfigurations::where('type', '=', ConfigurationTypes::LICENSE_CLASS->value)
            ->get();

        return view('modules.driverManagement.create')
            ->with(compact('licenseClasses'));
    }

    public function driverList(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $users = Driver::get();
        return view('modules.driverManagement.driverList')->with(compact('users'));
    }

    public function findDriver(Request $request): JsonResponse
    {
        $searchParam = strtoupper(trim($request->searchCriteria));
        $drivers = Driver::where('staff_number', '=', $searchParam)
            ->orWhere('name', 'LIKE', "%{$searchParam}%")
            ->get();

        /*$drivers = PHCMSEmployee::select('*')
            ->where('con_per_no', $searchParam)
            ->first();

        if (empty($drivers)) {
            $drivers = [];
        }*/


        if (empty($drivers)) {
            return response()->json([
                'success' => 'false',
                'payload' => [],
                'message' => 'No driver Found. Verify the input and ensure the employee was registered as an authorised driver.'
            ]);
        }

        return response()->json([
            'success' => true,
            'payload' => $drivers
        ]);

    }
}
