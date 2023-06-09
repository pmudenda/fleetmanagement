<?php

namespace App\Http\Controllers\Workflow;

use App\Constants\ErrorMessages;
use App\Enums\RequisitionItemTypes;
use App\Enums\RequisitionTypes;
use App\Enums\WorkflowProcessCodes;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\WorkflowTaskCreationFailedException;
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
            if ($requisitionDetail->requisition_type == RequisitionTypes::OutOfTown->value) {
                $process_code = WorkflowProcessCodes::OutOfTownFuelRequisition->value;
            } elseif ($requisitionDetail->requisition_type == RequisitionTypes::Normal->value) {
                $process_code = WorkflowProcessCodes::NormalFuelRequisition->value;
            } elseif ($requisitionDetail->requisition_type == RequisitionTypes::Override->value) {
                $process_code = WorkflowProcessCodes::OverrideFuelRequisition->value;
            }

            $action = 0;
            $actionTaken = '';
            $message = '';
            if ($request->get('Approved') === 'approve') {
                $action = WorkflowActions::approve();
                $actionTaken = "Approved";
                $message = 'Request Approved Successfully';
            } elseif ($request->get('Approved') === 'reject') {
                $action = WorkflowActions::rejected();
                $actionTaken = "Rejected";
                $message = 'Request Rejected';
            } elseif ($request->get('Approved') === 'send_back') {
                $action = WorkflowActions::sendBack();
                $actionTaken = "SendBack";
                $message = 'Request Sent Back To Originator';
            }

            $nextStepId = $this->workflowService->invokeWorkFlow(
                $reference,
                $process_code,
                $action,
                $actionTaken,
                $request->get('Comments')
            );

            if ($nextStepId == 100) {
                $this->requisitionService->createStoresRequisition($request->get('reference'));
            }

            return response()->json([
                'requestPayload' => $request->all(),
                'success' => true,
                'redirectUrl' => route('home'),
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');
            if ($e instanceof FuelRequisitionException || $e instanceof WorkflowTaskCreationFailedException) {
                $message = $e->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }

    public function processStoresRequisitionApproval(Request $request): JsonResponse
    {
        try {
            $reference = $request->get('reference');

            $requisitionDetail = $this->requisitionService->getRequisitionDetail($reference);

            switch ($requisitionDetail->item_type) {
                case RequisitionItemTypes::ServiceItemCode:
                case RequisitionItemTypes::StockItemCode:
                    $process_code = WorkflowProcessCodes::PurchaseProcess->value;
                    break;
                case RequisitionItemTypes::NonStockItemCode:
                    $process_code = WorkflowProcessCodes::StoresRequisition->value;
                    break;
                default:
                    break;
            }


            $actionTaken = '';
            $message = '';
            $action = 0;
            switch ($request->get('Approved')) {
                case 'approve':
                    $action = WorkflowActions::approve();
                    $actionTaken = "Approved";
                    $message = 'Request Approved Successfully';
                    break;
                case 'reject':
                    $action = WorkflowActions::rejected();
                    $actionTaken = "Rejected";
                    $message = 'Request Rejected';
                    break;
                case 'send_back':
                    $action = WorkflowActions::sendBack();
                    $actionTaken = "SendBack";
                    $message = 'Request Sent Back To Originator';
                    break;
            }

            $nextStepId = $this->workflowService->invokeWorkFlow(
                $reference,
                $process_code,
                $action,
                $actionTaken,
                $request->get('Comments')
            );

            if ($nextStepId == 100) {
                $this->requisitionService->createWorkshopMaterialStoresRequisition($request->get('reference'));
            }

            return response()->json([
                'requestPayload' => $request->all(),
                'success' => true,
                'redirectUrl' => route('home'),
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');
            if ($e instanceof FuelRequisitionException || $e instanceof WorkflowTaskCreationFailedException) {
                $message = $e->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }
}
