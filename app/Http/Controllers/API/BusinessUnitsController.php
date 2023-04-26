<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\general\BusinessUnits;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BusinessUnitsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {

            $month = 60 * 60 * 24 * 30;
            $data = cache()->remember('business_units', $month, function () {
                return BusinessUnits::orderBy('code_bu')->get();
            });

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
