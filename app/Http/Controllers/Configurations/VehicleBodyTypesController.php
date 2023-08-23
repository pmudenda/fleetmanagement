<?php

namespace App\Http\Controllers\Configurations;

use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\Settings\vehicle\VehicleBodyType;
use App\Services\Logging\HistoryService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $statusList = [StatusHelper::active()];
            $data = VehicleBodyType::whereIn('status', $statusList)->get();
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

            $vehicleBodyType = VehicleBodyType::where('body_type_name', '=', $name)->first();

            if (!empty($vehicleBodyType)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Body Type is already registered',
                    'payload' => []
                ]);
            }

            $model = VehicleBodyType::updateOrCreate(
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
                'message' => 'Record successfully',
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
    public function show(VehicleBodyType $configVehicleBodyType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleBodyType $configVehicleBodyType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleBodyType $configVehicleBodyType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = VehicleBodyType::where('guid', $request->input('guid'));

            $before = $data->toArray();

            $data->status = StatusHelper::inactive();
            $data->save();

            DB::commit();

            $after = $data->toArray();
            HistoryService::update($before, $after, 'Body Type', 'update', $request->get('justification'));

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
