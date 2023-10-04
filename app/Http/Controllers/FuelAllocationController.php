<?php

namespace App\Http\Controllers;

use App\Constants\SystemMessages;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Services\VehicleManagement\FuelAllocationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class FuelAllocationController extends Controller
{

    public function create(): View
    {
        return view('modules.vehicleManagement.fuelallocation');
    }

    public function save(Request $request): JsonResponse
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
