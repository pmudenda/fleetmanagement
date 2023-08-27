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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $registrationTypes = GeneralTable::where('type', ConfigurationTypes::REGISTRATION_CLASS)
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
