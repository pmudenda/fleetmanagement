<?php

namespace App\Http\Controllers\API;

use App\Constants\QueryComparisonOperator;
use App\Constants\TableColumns;
use App\Enums\ResponseState;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Common\BusinessUnit;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BusinessUnitsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $data = BusinessUnit::where(TableColumns::STATUS,
                QueryComparisonOperator::EQUALS,
                StatusHelper::active())
                ->orderBy('code_bu')
                ->get();
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::SUCCESS->value,
                    true,
                    '',
                    $data
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    ResponseState::FAILURE->value,
                    false,
                    '',
                    []
                )
            );
        }

    }
}
