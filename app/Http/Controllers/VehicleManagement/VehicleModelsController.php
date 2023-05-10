<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Enums\VehicleStatusEnum;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\configurations\vehicle\ConfigVehicleBrand;
use App\Models\configurations\vehicle\ConfigVehicleModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Str;

class VehicleModelsController extends Controller
{
    public function get(Request $request): JsonResponse
    {
        try {
            $data = ConfigVehicleModel::select(DB::raw('*'))
                //->groupBy('brand_guid')
                ->get();
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

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            $vehicleModel = ConfigVehicleBrand::where('model_code', '=', trim(strtoupper($request->input('model_code'))))
                ->where('model_name', '=', trim(strtoupper($request->input('model_code'))))
                ->first();

            if (!empty($vehicleModel)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Model already registered',
                    'payload' => []
                ]);
            }

            $model = ConfigVehicleModel::updateOrCreate(
                [
                'model_name' => trim(strtoupper($request->input('model_name'))),
                'model_code' => $request->input('model_code')
                ],
                [
                'status' => StatusHelper::active(),
                'model_guid' => Str::uuid(),
                'dateCreated' => Carbon::now(),
                'brand_guid' => $request->input('brand_guid'),
                'brand_name' => trim(strtoupper($request->input('brand_name'))),
                //'model_name' => trim(strtoupper($request->input('model_name'))),
                //'model_code' => $request->input('model_code')
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

    public function delete(Request $request): JsonResponse
    {
        try {
            $statusList = [VehicleStatusEnum::Active];
            $data = ConfigVehicleModel::whereIn('status', $statusList)
                ->get();
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
