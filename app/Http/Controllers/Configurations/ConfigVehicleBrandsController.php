<?php

namespace App\Http\Controllers\Configurations;

use App\Constants\ErrorMessages;
use App\Enums;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleMake;
use App\Models\Settings\vehicle\VehicleBrand;
use Exception;
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
    public function get(): JsonResponse
    {
        try {
            $statusList = [StatusHelper::active()];
            $data = VehicleBrand::whereIn('status', $statusList)->get();
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
     * Store a newly created resource in storage.
     */
    public function store(VehicleMake $request): JsonResponse
    {
        try {

            $make = VehicleBrand::where('name', '=', trim(strtoupper($request->input('brand_name'))))
                ->first();

            if (!empty($make)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle make is already registered',
                    'payload' => []
                ]);
            }

            $model = VehicleBrand::updateOrCreate(
                [
                    'name' => trim(strtoupper($request->input('brand_name'))),
                ],
                [
                    'name' => trim(strtoupper($request->input('brand_name'))),
                    'status' => StatusHelper::active(),
                    'guid' => Str::uuid(),
                    'dateCreated' => Carbon::now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Record Added Successfully',
                'payload' => $model
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Error Occurred while Processing request',
                'payload' => []
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $configVehicleBrands = VehicleBrand::where('code', $request->input('code'))->first();
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
                'message' => ErrorMessages::getMessage('err_0005'),
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
