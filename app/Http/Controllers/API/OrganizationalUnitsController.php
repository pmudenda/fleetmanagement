<?php

namespace App\Http\Controllers\API;

use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\Common\OrganizationalUnit;
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

            $query = OrganizationalUnit::query();

            $query->where('status', StatusHelper::organizationStructureActive());
            $query->whereNotNull('cc_code');
            $query->whereNotNull('bu_code');

            $data = $query->orderBy('description')->get();

            cache()->forget('org_units');
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
