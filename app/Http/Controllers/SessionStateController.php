<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class SessionStateController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $user = Auth()->user();
        if (!$user || $user->id == 0) {
            return response()->json(array(
                'message' => 'Session Expired',
                'state' => 'expired'
            ));
        }

        return response()->json(array(
            'message' => '',
            'state' => 'active',
        ));

    }
}
