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
use App\Services\Requisitions\WorkshopRequisitionService;
use App\Services\Workflow\WorkflowService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class WorkflowController extends Controller
{
    private FuelRequisitionService $fuelRequisitionService;
    private WorkshopRequisitionService $workshopRequisitionService;
    private WorkflowService $workflowService;

    public function __construct(FuelRequisitionService $requisitionService,
                                WorkflowService $workflowService,
                                WorkshopRequisitionService $workshopRequisitionService)
    {
        $this->fuelRequisitionService = $requisitionService;
        $this->workflowService = $workflowService;
        $this->workshopRequisitionService = $workshopRequisitionService;
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

            $requisitionDetail = $this->fuelRequisitionService->getRequisitionDetail($reference);

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
                $this->fuelRequisitionService->createStoresRequisition($request->get('reference'));
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

            $requisitionDetail = $this->workshopRequisitionService->getReservationDetail($reference);

            $process_code = '';
            switch ($requisitionDetail->item_type) {
                case RequisitionItemTypes::Service:
                case RequisitionItemTypes::NonStockItem:
                    $process_code = WorkflowProcessCodes::PurchaseProcess->value;
                    break;
                case RequisitionItemTypes::StockItem:
                    $process_code = WorkflowProcessCodes::StoresRequisition->value;
                    break;
                default:
                    break;
            }


            $actionTaken = '';
            $message = '';
            $action = 0;
            switch (strtolower(trim($request->get('Approved')))) {
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
                $this->workshopRequisitionService->createWorkshopMaterialStoresReservation($request->get('reference'));
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
