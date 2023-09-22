<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkShopManagement\PettyCashItems;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Services\WorkShopManagement\ImprestBuyService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ImprestBuysController extends Controller
{
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


}
