<?php

namespace App\Http\Controllers\Workflow;

use App\Constants\ErrorMessages;
use App\Constants\WorkflowActions;
use App\Enums\RequisitionItemTypes;
use App\Enums\RequisitionTypes;
use App\Enums\WorkflowProcessCodes;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\MaterialReservationException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Services\Requisitions\FuelRequisitionService;
use App\Services\Requisitions\WorkshopRequisitionService;
use App\Services\Workflow\WorkflowService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class WorkflowController extends Controller
{
    private FuelRequisitionService $fuelRequisitionService;
    private WorkshopRequisitionService $workshopRequisitionService;
    private WorkflowService $workflowService;

    public function __construct(FuelRequisitionService     $requisitionService,
                                WorkflowService            $workflowService,
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

            DB::beginTransaction();
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

            list($nextStepId, $nextUser) = $this->workflowService->invokeWorkFlow(
                $reference,
                $process_code,
                $action,
                $actionTaken,
                $request->get('Comments')
            );

            if ($nextStepId == 100) {
                $this->fuelRequisitionService->createStoresRequisition($request->get('reference'));

                $this->workshopRequisitionService->updateStatus($reference, StatusHelper::authorised());

                $this->workshopRequisitionService->updateMaterialHeaderStatus($reference, StatusHelper::authorised());
            } else {
                $status = '';
                switch (strtolower(trim($request->get('Approved')))) {
                    case 'approve':
                        $status = StatusHelper::partiallyAuthorised();
                        break;
                    case 'reject':
                        $status = StatusHelper::rejected();
                        break;
                }

                $this->fuelRequisitionService->updateStatus($reference, $status);
                $this->workshopRequisitionService->updateMaterialHeaderStatus($reference, $status);

            }

            DB::commit();

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

            DB::beginTransaction();
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
                    $message = 'Request Approved Successfully.';
                    break;
                case 'reject':
                    $action = WorkflowActions::rejected();
                    $actionTaken = "Rejected";
                    $message = 'Request Rejected.';
                    break;
                case 'send_back':
                    $action = WorkflowActions::sendBack();
                    $actionTaken = "SendBack";
                    $message = 'Request Sent Back To Originator.';
                    break;
            }

            list($nextStepId, $nextUser) = $this->workflowService->invokeWorkFlow(
                $reference,
                $process_code,
                $action,
                $actionTaken,
                $request->get('Comments')
            );

            if ($nextStepId == 100 && $action == WorkflowActions::approve()) {
                switch ($requisitionDetail->item_type) {
                    case RequisitionItemTypes::Service:
                        $purchaseProcessNumber = $this->workshopRequisitionService->createWorkshopServicePurchaseProcess($request->get('reference'));
                        $message = $message . ' Purchase Process No.: ' . $purchaseProcessNumber;
                        break;
                    case RequisitionItemTypes::NonStockItem:
                        $purchaseProcessNumber = $this->workshopRequisitionService->createWorkshopNonStockPurchaseProcess($request->get('reference'));
                        $message = $message . ' Purchase Process No.: ' . $purchaseProcessNumber;
                        break;
                    case RequisitionItemTypes::StockItem:
                        $reservationNumber = $this->workshopRequisitionService->createWorkshopMaterialStoresReservation($request->get('reference'));
                        $message = $message . ' Stores Reservation No.: ' . $reservationNumber;
                        break;
                    default:
                        throw new MaterialReservationException("ITEM TYPE NOT");
                }

                $this->workshopRequisitionService->updateStatus($reference, StatusHelper::authorised());
                $this->workshopRequisitionService->updateMaterialHeaderStatus($reference, StatusHelper::authorised());
            } else {

                $status = '';
                switch (strtolower(trim($request->get('Approved')))) {
                    case 'approve':
                        $message = 'Request Approved and Submitted to the Next Authority For Approval';
                        $status = StatusHelper::partiallyAuthorised();
                        break;
                    case 'reject':
                        $message = 'Request Rejected';
                        $status = StatusHelper::rejected();
                        break;
                }

                $this->workshopRequisitionService->updateStatus($reference, $status);
                $this->workshopRequisitionService->updateMaterialHeaderStatus($reference, $status);
            }

            DB::commit();
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

    public function processWorkOrderClosureApproval(Request $request): JsonResponse
    {
        try {

            $reference = $request->get('reference');

            //$requisitionDetail = $this->workshopRequisitionService->getReservationDetail($reference);

            DB::beginTransaction();

            /*;
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
            }*/

            $actionTaken = '';
            $process_code = WorkflowProcessCodes::WorkOrderClosure->value;
            $message = '';
            $action = 0;
            switch (strtolower(trim($request->get('Approved')))) {
                case 'approve':
                    $action = WorkflowActions::approve();
                    $actionTaken = "Approved";
                    $message = 'Request Approved Successfully.';
                    break;
                case 'reject':
                    $action = WorkflowActions::rejected();
                    $actionTaken = "Rejected";
                    $message = 'Request Rejected.';
                    break;
                case 'send_back':
                    $action = WorkflowActions::sendBack();
                    $actionTaken = "SendBack";
                    $message = 'Request Sent Back To Originator.';
                    break;
            }

            list($nextStepId, $nextUser) = $this->workflowService->invokeWorkFlow(
                $reference,
                $process_code,
                $action,
                $actionTaken,
                $request->get('Comments')
            );

            $user = Auth::user();
            if ($nextStepId == 100 && $action == WorkflowActions::approve()) {
                JobCardHeader::where("job_card_no", "=", str_replace('-C', '', $reference))
                    ->update([
                        'modified_by' => $user->staff_no,
                        'status' => StatusHelper::authorised(),
                        'updated_at' => Carbon::now()
                    ]);
            } else {
                $status = '';
                switch (strtolower(trim($request->get('Approved')))) {
                    case 'approve':
                        $message = 'Request Approved and Submitted to the Next Authority For Approval';
                        $status = StatusHelper::partiallyAuthorised();
                        break;
                    case 'reject':
                        $message = 'Request Rejected';
                        $status = StatusHelper::rejected();
                        break;
                }

                JobCardHeader::where("job_card_no", "=", str_replace('-C', '', $reference))
                    ->update([
                        'modified_by' => $user->staff_no,
                        'status' => $status,
                        'updated_at' => Carbon::now()
                    ]);
            }

            DB::commit();
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
