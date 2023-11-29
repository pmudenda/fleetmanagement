<?php

namespace App\Http\Controllers\API;
use App\Constants\SystemMessages;
use App\Enums\ResponseState;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RTSAIntegrationController extends Controller
{
    public function verifyLicenseDetails(): JsonResponse
    {
        try {

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    'Valid'
                ));
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    SystemMessages::LICENSE_VALID
                )
            );
        }

    }
}
