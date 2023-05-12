<?php

namespace App\Http\Controllers\OrganizationStructure;

use App\Http\Controllers\Controller;
use App\Models\configurations\GeneralTableConfigurations;
use App\Models\reference\BusinessAreas;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BusinessAreasController extends Controller
{
    public function get(): JsonResponse
    {
        try {

            $data = BusinessAreas::where('type', '=', 'businessAreas')->get();

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
