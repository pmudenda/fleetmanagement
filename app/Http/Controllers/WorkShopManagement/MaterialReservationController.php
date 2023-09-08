<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\ErrorMessages;
use App\Exceptions\MaterialReservationException;
use App\Exceptions\VehicleStateException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkShopManagement\WorkshopMaterialResevationRequest;
use App\Http\Requests\WorkShopManagement\WorkshopRequisitionRequest;
use App\Services\Requisitions\FuelRequisitionService;
use App\Services\Requisitions\WorkshopRequisitionService;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\WorkShopManagement\JobCardDetailsService;
use App\Services\WorkShopManagement\WorkshopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MaterialReservationController extends Controller
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
    public function saveJobCardMaterialRequest(WorkshopRequisitionRequest $request): JsonResponse
    {
        try {
            return $this->workshopRequisitionService->processJobCardMaterialRequisition($request);
        } catch (\Exception $e) {
            $message = ErrorMessages::getMessage("err_0005");
            if ($e instanceof MaterialReservationException
                || $e instanceof WorkflowTaskCreationFailedException
                || $e instanceof VehicleStateException) {
                $message = $e->getMessage();
                Log::info($e);
            } else {
                Log::error($e);
            }
            return response()->json([
                "success" => false,
                "message" => $message
            ]);
        }
    }

    public function saveMaterialRequest(WorkshopMaterialResevationRequest $request): JsonResponse
    {
        try {
            return $this->workshopRequisitionService->processMaterialReservation($request);
        } catch (\Exception $e) {

            $message = ErrorMessages::getMessage("err_0005");
            if ($e instanceof MaterialReservationException
                || $e instanceof WorkflowTaskCreationFailedException
                || $e instanceof VehicleStateException) {
                $message = $e->getMessage();
            } else {
                Log::error($e);
            }
            return response()->json([
                "success" => false,
                "message" => $message
            ]);
        }
    }

}
