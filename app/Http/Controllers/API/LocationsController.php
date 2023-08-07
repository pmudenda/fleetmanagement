<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Reference\LocationsModel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LocationsController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $month = 60 * 60 * 24 * 30;
            $data = cache()->remember('location', $month, function () {
                return LocationsModel::get();
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
