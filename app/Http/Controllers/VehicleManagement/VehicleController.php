<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Http\Controllers\Controller;
use App\Models\vehiclemanagement\VehicleHeader;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller
{

    public function getDetails(Request $request): JsonResponse
    {
        try {
            $vehicle = VehicleHeader::where('registration_number', $request->vehicle_registration)
                ->first();
            return response()->json([
                'payload' => $vehicle,
                'success' => !empty($vehicle)
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => 'false'
            ]);
        }
    }
}
