<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller
{

    public function getDetails(Request $request): JsonResponse
    {
        try {
            if (empty($request->vehicle_registration)) {
                return response()->json([
                    'success' => 'false',
                    'statusDescription' => 'Bad Request',
                    'message' => 'Missing required parameter'
                ]);
            }
            /*$vehicle = VehicleHeader::where('registration_number', $request->vehicle_registration)
                ->first();*/
            // determine material type in form of fuel
            $vehicle = DB::table('VM_VEHICLE_HEADER')->
            where('registration_number', $request->vehicle_registration)
                ->leftJoin('VM_ASSIGNMENTS', 'VM_VEHICLE_HEADER.id', '=', 'VM_ASSIGNMENTS.vehicle_header_id')
                ->leftJoin('VM_ENGINE_DETAILS', 'VM_VEHICLE_HEADER.id', '=', 'VM_ENGINE_DETAILS.vehicle_header_id')
                ->select('VM_VEHICLE_HEADER.*', 'VM_ASSIGNMENTS.*', 'VM_ENGINE_DETAILS.fuel_allocation', 'VM_ENGINE_DETAILS.fuel_types')
                ->first();
            return response()->json([
                'payload' => $vehicle,
                'success' => !empty($vehicle),
                'message' => 'Details retrieved successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => 'false',
                'message' => 'We could not complete processing your request, Please contact System Administrator'
            ]);
        }
    }
}
