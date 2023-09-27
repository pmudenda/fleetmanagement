<?php

namespace App\Http\Controllers\API;

use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Common\BusinessUnit;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BusinessUnitsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $data = BusinessUnit::where('status', '=', StatusHelper::active())
                ->orderBy('code_bu')
                ->get();
            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
                    true,
                    '',
                    $data
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    '',
                    []
                )
            );
        }

    }
}
