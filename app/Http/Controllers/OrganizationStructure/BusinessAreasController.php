<?php

namespace App\Http\Controllers\OrganizationStructure;

use App\Enums\ResponseState;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Reference\Area;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BusinessAreasController extends Controller
{
    public function get(): JsonResponse
    {
        try {
            $data = Area::get();
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    null,
                    $data
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(FleetMasterJsonResponse::response(
                ResponseState::FAILURE->value,
                false,
                null,
                []
            ));
        }

    }
}
