<?php

namespace App\Http\Controllers\OrganizationStructure;

use App\Http\Controllers\Controller;
use App\Models\general\DIRECTORATES;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DirectoratesController extends  Controller
{

    public function get(Request $request) {
        try {
            cache()->forget('directorates');
            $month = 60 * 60 * 24 * 30;
            // clear the cache using request
            if ($request->has('cache') && !$request->get('cache')) {
                cache()->forget('directorates');
            }

            $data = cache()->remember('directorates', $month, function () {
                return DIRECTORATES::orderBy('name')->get();
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
