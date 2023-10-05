<?php

namespace App\Http\Controllers\API;

use App\Enums\ResponseState;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Reference\LocationsModel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LocationsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $month = 60 * 60 * 24 * 30;
            $data = cache()->remember('location',
                $month,
                function () {
                    return LocationsModel::get();
                });

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
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    null,
                    []
                )
            );
        }

    }
}
