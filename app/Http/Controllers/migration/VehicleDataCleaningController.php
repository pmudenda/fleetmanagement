<?php

namespace App\Http\Controllers\migration;

use App\Http\Controllers\Controller;
use App\Models\reference\GtaVehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleDataCleaningController extends Controller
{
    public function cleanUpList(Request $request): View
    {
        $userUnits = [];
        $vehicleList = [];
        try {
            if ($request->has('userUnit')) {
                $vehicleList = GtaVehicle::where('codigo_unidad', '=', $request->get('userUnit'))->get();
            }
            $userUnits =DB::select(DB::raw('distinct gta.codigo_unidad, ou.description as name_dec from
                                                                      GTAVEHIC_VIEW gta
                                                                          inner join
                                                                          ref_organizational_units ou
                                                                              on gta.codigo_unidad = ou.code_unit'))->get();
            return view('modules.vehicleManagement.migration.list')->with(compact(
                'vehicleList', 'userUnits'
            ));
        } catch (\Exception $e) {
            Log::error($e);
            return view('modules.vehicleManagement.migration.list')->with(compact(
                'vehicleList', 'userUnits'
            ));
        }
    }

    public function cleanUpWindow(Request $request): View
    {
        $req = $request->get('reg');
        return view('modules.vehicleManagement.migration.index')
            ->with(compact('req'));
    }


    public function filter(Request $request): View
    {
        $vehicleList = [];
        if ($request->has('userUnit')) {
            $vehicleList = GtaVehicle::where('codigo_unidad', '=', $request->get('userUnit'))->get();
        }

        $userUnits = DB::raw('select distinct gta.codigo_unidad, ou.description as name_dec from
                                                                  GTAVEHIC_VIEW gta
                                                                      inner join
                                                                      ref_organizational_units ou
                                                                          on gta.codigo_unidad = ou.code_unit')
            ->get();
        return view('modules.vehicleManagement.migration.list')->with(compact(
            'vehicleList', 'userUnits'
        ));
    }


}
