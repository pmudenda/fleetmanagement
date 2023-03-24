<?php

namespace App\Http\Controllers\Configurations;

use App\Http\Controllers\Controller;
use App\Models\configurations\vehicle\ConfigVehicleBrand;
use Exception;
use App\Enums;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ConfigVehicleBrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $statusList = [Enums\VehicleStatusEnum::Active];
            $data = ConfigVehicleBrand::whereIn('status', $statusList)->get();
            return response()->json([
                'state' => 'success',
                'payload' => $data
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => []
            ]);
        }
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
    public function store(Request $request): JsonResponse
    {
        try {

            $data = $request->all();

            // TODO validation

            $model = ConfigVehicleBrand::create([
                'name' => trim(strtoupper($request->input('name'))),
                'status' => $request->input('status'),
                'guid' => Str::uuid(),
                'dateCreated' => Carbon::now()
            ]);

            return response()->json([
                'state' => 'success',
                'message' => '',
                'payload' => $model
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'message' => 'Error Occurred while Processing request',
                'payload' => []
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ConfigVehicleBrand $configVehicleBrands)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConfigVehicleBrand $configVehicleBrands)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConfigVehicleBrand $configVehicleBrands)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $configVehicleBrands = ConfigVehicleBrand::where('guid', $request->input('guid'))->first();
            $configVehicleBrands->status = 'deactivated';
            $configVehicleBrands->status = 'deactivated';

            return response()->json([
                'state' => 'success',
                'message' => 'Deleted Successfully',
                'payload' => []
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'message' => 'Error Occurred while Processing request',
                'payload' => []
            ]);
        }
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
