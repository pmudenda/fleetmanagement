<?php

namespace App\Http\Controllers\migration;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OrganizationStructure\DirectoratesController;
use App\Http\Requests\DataCleanUp;
use App\Models\Settings\vehicle\VehicleBrand;
use App\Models\Reference\GtaVehicle;
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
            $userUnits = DB::table('GTAVEHIC_VIEW as gta')
                ->select(DB::raw('distinct gta.codigo_unidad, ou.description as name_dec'))
                ->join('zfm_organizational_units_view as ou', 'gta.codigo_unidad', '=', 'ou.code_unit')
                ->get();

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
        $registration = $request->get('reg');
        $vehicleMakes = VehicleBrand::get();

        $colors = collect([
            (object)['name'=>"Black", 'code'=>'BLACK'],
            (object)['name'=>"Red", 'code'=>'RED'],
            (object)['name'=>"Blue", 'code'=>'BLUE'],
            (object)['name'=>"White", 'code'=>'WHITE'],
            (object)['name'=>"Gray", 'code'=>'GRAY'],
        ]);
        return view('modules.vehicleManagement.migration.index')
            ->with(compact('registration',
                'colors',
                'vehicleMakes'));
    }


    public function filter(Request $request): View
    {
        $vehicleList = [];
        if ($request->has('userUnit')) {
            $vehicleList = GtaVehicle::where('codigo_unidad', '=', $request->get('userUnit'))->get();
        }

        $userUnits = DB::table('GTAVEHIC_VIEW as gta')
            ->select(DB::raw('distinct gta.codigo_unidad, ou.description as name_dec'))
            ->join('zfm_organizational_units_view as ou', 'gta.codigo_unidad', '=', 'ou.code_unit')
            ->get();

        return view('modules.vehicleManagement.migration.list')->with(compact(
            'vehicleList', 'userUnits'
        ));
    }


    public function saveData(DataCleanUp $request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'state' => 'success',
            'message' => 'Request Processed Successfully'
        ]);
    }

}
