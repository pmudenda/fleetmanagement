<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Exceptions\InvalidAssessmentSignatoryException;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Driver;
use App\Models\Reference\PHCMSEmployee;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Services\WorkShopManagement\WorkshopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class VehicleAssessmentController
{
    private WorkshopService $workshopService;

    public function __construct(WorkshopService $workshopService)
    {
        $this->workshopService = $workshopService;
    }

    public function save(Request $request): JsonResponse
    {
        try {
            $this->workshopService->processJobCardAVehicleAssessment($request);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
                    true,
                    SystemMessages::accessoriesCheckedIn(),
                    null,
                    URL::signedRoute("vehicle.workshop.checkin",
                        ["step" => 3, "reference" => $request->get("job_card_voucher")])
                )
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    ErrorMessages::getMessage("err_0005")
                )
            );
        }
    }
}
