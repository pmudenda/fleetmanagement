<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcurementSystemIntegrationController extends \App\Http\Controllers\Controller
{
    public function verify(Request $request): JsonResponse
    {
        return response()->json([
            'state' => 'success',
            'payload' => [],
            'message' => ''
        ]);
    }
}
