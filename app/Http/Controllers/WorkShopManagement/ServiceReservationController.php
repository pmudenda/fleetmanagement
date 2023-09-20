<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\ErrorMessages;
use App\Exceptions\MaterialReservationException;
use App\Exceptions\VehicleStateException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkShopManagement\WorkshopServiceRequisitionRequest;
use App\Http\Requests\WorkShopManagement\WorkshopServiceReservationRequest;
use App\Services\Requisitions\WorkshopRequisitionService;
use App\Services\WorkShopManagement\WorkshopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ServiceReservationController extends Controller
{
    private WorkshopService $workshopService;
    private WorkshopRequisitionService $workshopRequisitionService;

    public function __construct(WorkshopService                 $workshopService,
                                WorkshopRequisitionService      $workshopRequisitionService)
    {
        $this->workshopService = $workshopService;
        $this->workshopRequisitionService = $workshopRequisitionService;
    }
    public function saveJobCardService(WorkshopServiceRequisitionRequest $request): JsonResponse
    {
        try {
            return $this->workshopRequisitionService->processJobCardServiceRequest($request);
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage("err_0005");

            if ($e instanceof MaterialReservationException
                || $e instanceof WorkflowTaskCreationFailedException
                || $e instanceof VehicleStateException) {
                $message = $e->getMessage();
            }
            return response()->json([
                "success" => false,
                "message" => $message
            ]);
        }
    }

    public function saveServiceBooking(WorkshopServiceReservationRequest $request): JsonResponse
    {
        try {
            return $this->workshopRequisitionService->processServiceReservation($request);
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage("err_0005");

            if ($e instanceof MaterialReservationException
                || $e instanceof WorkflowTaskCreationFailedException
                || $e instanceof VehicleStateException) {
                $message = $e->getMessage();
            }
            return response()->json([
                "success" => false,
                "message" => $message
            ]);
        }
    }

}
