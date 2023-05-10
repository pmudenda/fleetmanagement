<?php

namespace App\Http\Controllers\OrganizationStructure;

use App\Http\Controllers\Controller;
use App\Models\configurations\GeneralTableConfigurations;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BusinessAreasController extends Controller
{
    public function get(): JsonResponse
    {
        try {

            $data = GeneralTableConfigurations::where('type', '=', 'businessAreas')->get();
            //BusinessAreas::get();

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
