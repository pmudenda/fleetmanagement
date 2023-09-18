<?php

namespace App\Http\Controllers\Workflow;

use App\Constants\ErrorMessages;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\MaterialRequisitionException;
use App\Exceptions\MaterialReservationException;
use App\Exceptions\ServiceRequisitionException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Services\Integration\ProcurementSystemIntegrationService;
use App\Services\Requisitions\FuelRequisitionService;
use App\Services\Requisitions\WorkshopRequisitionService;
use App\Services\Workflow\WorkflowService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class WorkflowController extends Controller
{
    const APPROVED = 'Request Approved and Submitted to the Next Authority For Approval ';
    private FuelRequisitionService $fuelRequisitionService;
    private WorkshopRequisitionService $workshopRequisitionService;
    private WorkflowService $workflowService;
    private ProcurementSystemIntegrationService $procurementService;

    public function __construct(FuelRequisitionService              $requisitionService,
                                WorkflowService                     $workflowService,
                                WorkshopRequisitionService          $workshopRequisitionService,
                                ProcurementSystemIntegrationService $procurementService)
    {
        $this->fuelRequisitionService = $requisitionService;
        $this->workflowService = $workflowService;
        $this->workshopRequisitionService = $workshopRequisitionService;
        $this->procurementService = $procurementService;
    }

    public function showWorkTask(): View
    {
        $workTask = WorkflowTaskHeader:: orderBy('created_at', 'ASC')->get();
        return view('modules.workflow.approvals', compact(['workTask']))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function viewTasks(): View
    {
        $user = auth()->user();
        $approvalTasks = $this->workflowService->getMyApprovalTasks($user->staff_no);

        return view('dashboard.home')
            ->with(compact('approvalTasks'));
    }

    public function processFuelRequisitionApproval(Request $request): JsonResponse
    {
        try {
            $reference = $request->get('reference');
            $submittedAction = $request->get('Approved');
            $remarks = $request->get('Comments');

            $message = $this->fuelRequisitionService->processFuelRequisitionWorkflow(
                $reference,
                $submittedAction,
                $remarks
            );
            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
                    true,
                    $message,
                    null,
                    route('home')
                )
            );
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');

            if ($e instanceof FuelRequisitionException
                || $e instanceof WorkflowTaskCreationFailedException) {
                $message = $e->getMessage();
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

    public function processStoresRequisitionApproval(Request $request): JsonResponse
    {
        try {

            $message = $this->workshopRequisitionService->processWorkshopRequisitionWorkflow($request);
            return response()->json(
                FleetMasterJsonResponse::response(
                    'success',
                    true,
                    $message,
                    [],
                    route('home')
                )
            );
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');
            if ($e instanceof ServiceRequisitionException
                || $e instanceof WorkflowTaskCreationFailedException
                || $e instanceof MaterialRequisitionException
                || $e instanceof MaterialReservationException
            ) {
                $message = $e->getMessage();
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
