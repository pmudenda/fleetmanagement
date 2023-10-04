<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Constants\SystemMessages;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Services\VehicleManagement\FuelAllocationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class StatusChangeController extends Controller
{
    public function create(): View
    {
        return view('modules.vehicleManagement.statusChange');
    }

    public function store(Request $request): JsonResponse
    {
        try {
            FuelAllocationService::fuelAllocation($request);
            return response()->json(
                FleetMasterJsonResponse::response(
                    '',
                    true,
                    SystemMessages::FUEL_ALLOCATION_SET
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    '',
                    false,
                    SystemMessages::FUEL_ALLOCATION_FAILED
                )
            );
        }
    }


}
