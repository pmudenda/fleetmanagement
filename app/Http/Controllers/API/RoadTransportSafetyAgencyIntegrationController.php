<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\reference\LocationsModel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RoadTransportSafetyAgencyIntegrationController extends Controller
{
    public function verifyLicenseDetails(): JsonResponse
    {
        try {

            return response()->json([
                'success' => true,
                'payload' => [],
                'message' => 'Valid'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => 'false',
                'payload' => [],
                'message' => 'License Details Could not be verified'
            ]);
        }

    }
}
