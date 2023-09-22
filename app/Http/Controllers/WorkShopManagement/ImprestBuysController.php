<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkShopManagement\PettyCashItems;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\WorkShopManagement\ImprestBuyDetail;
use App\Models\WorkShopManagement\ImprestBuyHeader;
use App\Services\WorkShopManagement\ImprestBuyService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImprestBuysController extends Controller
{
    const RECORD_REMOVED_SUCCESSFULLY = "Record Removed Successfully";

    private ImprestBuyService $imprestBuyService;

    public function __construct(ImprestBuyService $imprestBuyService)
    {
        $this->imprestBuyService = $imprestBuyService;
    }

    public function saveImprestBuyItems(PettyCashItems $request): JsonResponse
    {
        try {
            $this->imprestBuyService->save($request);

            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
                    true,
                    'Imprest Requested Successfully',
                    $request->all()
                ));

        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    $request->all()
                )
            );
        }
    }

    public function deletePettyCashItem(Request $request): JsonResponse
    {
        try {
            $this->imprestBuyService->voidPettyCash($request);
            return response()->json(FleetMasterJsonResponse::response(
                'success',
                true,
                self::RECORD_REMOVED_SUCCESSFULLY
            ));

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    ErrorMessages::getMessage('err_0005')
                )
            );
        }
    }
}
