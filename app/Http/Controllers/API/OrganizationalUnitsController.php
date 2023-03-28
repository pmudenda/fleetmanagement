<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\general\OrganizationalUnits;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrganizationalUnitsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $month = 60 * 60 * 24 * 30;

            $query = OrganizationalUnits::query();

            if (!$request->get('include_nulls')) {
                $query->whereNotNull('description');
            }

            //$data = null;
            if ($request->get('org_units')) {
                $data = cache()->remember('org_units', $month, function ($query) {
                    return $query->orderBy('description')->get();
                });
            } else {
                cache()->forget('org_units');
                $data = $query->orderBy('description')->get();
            }

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
