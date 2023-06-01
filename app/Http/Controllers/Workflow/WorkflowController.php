<?php

namespace App\Http\Controllers\Workflow;

use App\Constants\ErrorMessages;
use App\Enums\RequisitionTypes;
use App\Enums\WorkflowProcessCodes;
use App\Exceptions\FuelRequisitionException;
use App\Http\Controllers\Controller;
use App\Models\Workflow\WorkflowActions;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Services\Requisitions\FuelRequisitionService;
use App\Services\Workflow\WorkflowService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class WorkflowController extends Controller
{
    private FuelRequisitionService $requisitionService;
    private WorkflowService $workflowService;

    public function __construct(FuelRequisitionService $requisitionService, WorkflowService $workflowService)
    {
        $this->requisitionService = $requisitionService;
        $this->workflowService = $workflowService;
    }

    public function showWorkTask(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $workTask = WorkflowTaskHeader:: orderBy('created_at', 'ASC')->get();
        return view('modules.workflow.approvals', compact(['workTask']))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function processFuelRequisitionApproval(Request $request): JsonResponse
    {
        try {
            $reference = $request->get('reference');

            $requisitionDetail = $this->requisitionService->getRequisitionDetail($reference);

            $process_code = '';
            if ($requisitionDetail->requisition_type == RequisitionTypes::OutOfTown) {
                $process_code = WorkflowProcessCodes::OutOfTownFuelRequisition->value;
            } elseif ($requisitionDetail->requisition_type == RequisitionTypes::Normal) {
                $process_code = WorkflowProcessCodes::NormalFuelRequisition->value;
            } elseif ($requisitionDetail->requisition_type == RequisitionTypes::Override) {
                $process_code = WorkflowProcessCodes::OverrideFuelRequisition->value;
            }

            $action = '';
            $actionTaken = '';
            if ($request->get('Approved') === 'approve') {
                $action = WorkflowActions::approve();
                $actionTaken = "Approved";
            } elseif ($request->get('Approved') === 'reject') {
                $action = WorkflowActions::rejected();
                $actionTaken = "Rejected";
            } elseif ($request->get('Approved') === 'send_back') {
                $action = WorkflowActions::sendBack();
                $actionTaken = "SendBack";
            }

           $nextStepId = $this->workflowService->invokeWorkFlow(
                $reference,
                $action,
                $actionTaken,
                $request->get('Comments'),
                $process_code
            );

            if($nextStepId == 100){
                $this->requisitionService->processFuelRequisitionApproval(
                    $request->get('reference'));
            }

            return response()->json([
                'requestPayload' => $request->all(),
                'success' => true,
                'redirectUrl' => route('list.fuel.requisition'),
                'message' => 'Request Approved Successfully'
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::internalServerError;
            if ($e instanceof FuelRequisitionException) {
                $message = $e->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }
}
