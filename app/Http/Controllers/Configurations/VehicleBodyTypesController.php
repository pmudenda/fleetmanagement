<?php

namespace App\Http\Controllers\Configurations;

use App\Enums;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleBodyType;
use App\Models\configurations\vehicle\ConfigVehicleBodyType;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VehicleBodyTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $statusList = [Enums\VehicleStatusEnum::Active, Enums\VehicleStatusEnum::active];
            $data = ConfigVehicleBodyType::whereIn('status', $statusList)->get();
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
    public function store(VehicleBodyType $request): JsonResponse
    {
        try {

            $name = trim(strtoupper($request->input('body_type_name')));

            $vehicleBodyType = ConfigVehicleBodyType::where('body_type_name', '=', $name)->first();

            if (!empty($vehicleBodyType)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Body Type is already registered',
                    'payload' => []
                ]);
            }

            $model = ConfigVehicleBodyType::updateOrCreate(
                [
                    'body_type_name' => $name
                ],
                [
                    'status' => StatusHelper::active(),
                    'guid' => Str::uuid(),
                    'dateCreated' => Carbon::now(),
                    'name' => $name,
                    'body_type_name' => $name
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
    public function show(ConfigVehicleBodyType $configfVehicleBodyType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConfigVehicleBodyType $configfVehicleBodyType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConfigVehicleBodyType $configfVehicleBodyType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    //public function destroy(ConfigVehicleBodyType $configfVehicleBodyType)
    public function destroy(Request $request): JsonResponse
    {
        try {

            $data = ConfigVehicleBodyType::where('guid', $request->input('guid'));
            $data->status = 'inactive'; //TODO Use Status enum
            $data->save();

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
}
