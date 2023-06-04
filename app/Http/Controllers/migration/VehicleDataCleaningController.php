<?php

namespace App\Http\Controllers\migration;

use App\Http\Controllers\Controller;
use App\Models\reference\GtaVehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class VehicleDataCleaningController extends Controller
{
    public function cleanUpList(Request $request): View
    {
        $vehicleList = [];
        if ($request->has('userUnit')) {
            $vehicleList = GtaVehicle::where('codigo_unidad', '=', $request->get('userUnit'))->get();
        }
        return view('modules.vehicleManagement.migration.list')->with(compact(
            'vehicleList'
        ));
    }

    public function filter(Request $request): View
    {
        $vehicleList = [];
        if ($request->has('userUnit')) {
            $vehicleList = GtaVehicle::where('codigo_unidad', '=', $request->get('userUnit'))->get();
        }
        return view('modules.vehicleManagement.migration.list')->with(compact(
            'vehicleList'
        ));
    }


}
