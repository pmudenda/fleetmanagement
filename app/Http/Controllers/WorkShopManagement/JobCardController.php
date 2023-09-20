<?php

namespace App\Http\Controllers\WorkShopManagement;

use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\WorkShopManagement\WorkshopRequisitionService;
use App\Services\WorkShopManagement\WorkshopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class JobCardController extends Controller
{
    private WorkshopService $workshopService;
    private DocumentNumberGenerationService $numberGeneratorService;
    private WorkshopRequisitionService $workshopRequisitionService;

    public function __construct(WorkshopService                 $workshopService,
                                DocumentNumberGenerationService $numberGeneratorService,
                                WorkshopRequisitionService      $workshopRequisitionService)
    {
        $this->workshopService = $workshopService;
        $this->numberGeneratorService = $numberGeneratorService;
        $this->workshopRequisitionService = $workshopRequisitionService;
    }

    public function list(Request $request): View
    {
        $this->verifyRequestSignature($request);

        if ($request->has('getRecords')) {
            Log::debug("Get Records Present");
            $workshopsVehicleList = $this->workshopService->getJobCardHeader(StatusHelper::new(), $request);
        } else {
            $workshopsVehicleList = $this->workshopService->getJobCardHeader(StatusHelper::new());
        }

        return view("modules.workshopManagement.vehiclesInWorkshop")
            ->with(
                compact(
                    "workshopsVehicleList"
                )
            );
    }

    public function viewClosedJobCards(Request $request): View
    {
        $this->verifyRequestSignature($request);

        $workshopsVehicleList = $this->workshopService->getJobCardHeader(StatusHelper::closed());

        return view("modules.workshopManagement.closedJobCards")
            ->with(
                compact(
                    "workshopsVehicleList"
                )
            );
    }

    /**
     * @param Request $request
     * @return void
     */
    public function verifyRequestSignature(Request $request): void
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
    }

}
