<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Enums\ConfigurationTypes;
use App\Http\Controllers\Controller;
use App\Models\Settings\GeneralTable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MeterEntryController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        $registrationTypes = GeneralTable::where('type',
            '=',
            ConfigurationTypes::REGISTRATION_CLASS)
            ->where('active', '=', "1")
            ->get();

        return view('modules.vehicleManagement.odometerLogs.create')
            ->with(compact('registrationTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // build log
    }

}
