<?php

namespace App\Http\Controllers\Configurations;

use App\Http\Controllers\Controller;
use App\Models\configurations\vehicle\ConfigVehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigVehicleModelController extends Controller
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
    public function create()
    {
        //
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
    public function show(ConfigVehicleModel $configVehicleModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConfigVehicleModel $configVehicleModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConfigVehicleModel $configVehicleModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConfigVehicleModel $configVehicleModel)
    {
        //
    }

    public function validateRequest(Request $request, $validationFields): \Illuminate\Contracts\Validation\Validator
    {
        if ($validationFields[0] != 'all') {
            $rules = [];
            $messages = [];
            foreach ($validationFields as $validationField) {
                $rules = [$validationField => ['required']];
                $messages = [$validationField => 'You have not provided valid data for ' . $validationField];
            }

            // request, rules, messages
            return Validator::make(
                $request->all(),
                $rules, $messages
            );
        }
        return Validator::make(
            $request->all(),
            [
                'taskOriginator' => ['required'],
            ],
            [
                'taskAssignee' => 'Please select task assignee',
            ]
        );
    }
}
