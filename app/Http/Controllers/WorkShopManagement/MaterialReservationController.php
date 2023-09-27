<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Constants\ErrorMessages;
use App\Exceptions\BaseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkShopManagement\MaterialReservationRequest;
use App\Http\Requests\WorkShopManagement\WorkshopRequisitionRequest;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\WorkShopManagement\JobCardDetailsService;
use App\Services\WorkShopManagement\WorkshopRequisitionService;
use App\Services\WorkShopManagement\WorkshopService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MaterialReservationController extends Controller
{
    private WorkshopService $workshopService;
    private DocumentNumberGenerationService $numberGeneratorService;
    private WorkshopRequisitionService $workshopRequisitionService;
    private readonly JobCardDetailsService $jobCardDetailsService;

    public function __construct(WorkshopService                 $workshopService,
                                DocumentNumberGenerationService $numberGeneratorService,
                                WorkshopRequisitionService      $workshopRequisitionService,
                                JobCardDetailsService           $jobCardDetailsService)
    {
        $this->workshopService = $workshopService;
        $this->numberGeneratorService = $numberGeneratorService;
        $this->workshopRequisitionService = $workshopRequisitionService;
        $this->jobCardDetailsService = $jobCardDetailsService;
    }

    public function saveJobCardMaterialRequest(WorkshopRequisitionRequest $request): JsonResponse
    {
        try {
            return $this->workshopRequisitionService->processJobCardMaterialRequisition($request);
        } catch (\Exception $e) {
            $message = ErrorMessages::getMessage("err_0005");
            if ($e instanceof BaseException) {
                $message = $e->getMessage();
                Log::info($e);
            } else {
                Log::error($e);
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    $message
                )
            );
        }
    }

    public function saveMaterialRequest(MaterialReservationRequest $request): JsonResponse
    {
        try {
            return $this->workshopRequisitionService->processMaterialReservation($request);
        } catch (Exception $e) {
            $message = ErrorMessages::getMessage("err_0005");
            if ($e instanceof BaseException) {
                $message = $e->getMessage();
            } else {
                Log::error($e);
            }
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    $message
                )
            );
        }
    }

}
