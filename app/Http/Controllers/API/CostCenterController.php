<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Common\CostCenter;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CostCenterController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $data = CostCenter::orderBy('description')->get();

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
