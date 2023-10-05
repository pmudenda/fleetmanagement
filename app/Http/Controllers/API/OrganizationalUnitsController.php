<?php

namespace App\Http\Controllers\API;

use App\Constants\TableColumns;
use App\Enums\ResponseState;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
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
            $month = 60 * 60 * 24;

            $query = OrganizationalUnit::query();

            $query->where(TableColumns::STATUS,
                StatusHelper::organizationStructureActive())
                ->whereNotNull('cc_code')
                ->whereNotNull('bu_code');

            if ($request->get('org_units')) {
                $data = cache()->remember('org_units', $month, function ($query) {
                    return $query->orderBy('description')->get();
                });
            } else {
                $data = $query->orderBy('description')->get();
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    null,
                    $data
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    null,
                    []
                )
            );
        }

    }
}
