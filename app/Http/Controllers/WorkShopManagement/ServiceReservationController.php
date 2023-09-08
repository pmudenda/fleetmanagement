<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\ErrorMessages;
use App\Exceptions\MaterialReservationException;
use App\Exceptions\VehicleStateException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkShopManagement\WorkshopServiceRequisitionRequest;
use App\Http\Requests\WorkShopManagement\WorkshopServiceReservationRequest;
use App\Services\Requisitions\FuelRequisitionService;
use App\Services\Requisitions\WorkshopRequisitionService;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\WorkShopManagement\JobCardDetailsService;
use App\Services\WorkShopManagement\WorkshopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ServiceReservationController extends Controller
{
    private WorkshopService $workshopService;
    private DocumentNumberGenerationService $numberGeneratorService;
    private FuelRequisitionService $fuelRequisitionService;
    private WorkshopRequisitionService $workshopRequisitionService;
    private readonly JobCardDetailsService $jobCardDetailsService;

    public function __construct(WorkshopService                 $workshopService,
                                DocumentNumberGenerationService $numberGeneratorService,
                                FuelRequisitionService          $requisitionService,
                                WorkshopRequisitionService      $workshopRequisitionService,
                                JobCardDetailsService           $jobCardDetailsService)
    {
        $this->workshopService = $workshopService;
        $this->numberGeneratorService = $numberGeneratorService;
        $this->fuelRequisitionService = $requisitionService;
        $this->workshopRequisitionService = $workshopRequisitionService;
        $this->jobCardDetailsService = $jobCardDetailsService;
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
