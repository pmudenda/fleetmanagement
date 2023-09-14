<?php

namespace App\Http\Controllers\Workflow;

use App\Constants\ErrorMessages;
use App\Constants\WorkflowActions;
use App\Enums\RequisitionItemTypes;
use App\Enums\RequisitionTypes;
use App\Enums\WorkflowProcessCodes;
use App\Events\FuelRequisitionApproved;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\MaterialRequisitionException;
use App\Exceptions\MaterialReservationException;
use App\Exceptions\ServiceRequisitionException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class WorkflowController extends Controller
{
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
                $action = WorkflowActions::reject();
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
            if (empty($nextUser)) {
                $nextUser = '';
            }

            $requisitionNumber = null;
            if ($nextStepId == 100 && $action == WorkflowActions::approve()) {
                $requisitionNumber = $this->fuelRequisitionService->createStoresRequisition($request->get('reference'));
                $message = $message . ' Stores Requisition No.: ' . $requisitionNumber;
                $this->fuelRequisitionService->updateStatus($reference, StatusHelper::authorised());
            } elseif ($nextStepId == 100 && $action == WorkflowActions::reject()) {
                $status = StatusHelper::rejected();
                $message = 'Request Rejected.';
                $this->fuelRequisitionService->updateStatus($reference, $status);
            } else {
                $status = '';
                if (strtolower(trim($request->get('Approved'))) == 'approve') {
                    $status = StatusHelper::partiallyAuthorised();
                    $message = 'Request Approved and Submitted to the Next Authority For Approval ' .
                        $nextUser;
                } elseif ($action == WorkflowActions::sendBack()) {
                    $status = StatusHelper::sentBack();
                    $message = 'Request Returned to Originator';
                }
                $this->fuelRequisitionService->updateStatus($reference, $status);
            }

            DB::commit();

            if ($nextStepId == 100) {
                FuelRequisitionApproved::dispatch($reference, Auth::user(), 'fullyAuthorised', $requisitionNumber);
            } else {
                if ($action == WorkflowActions::sendBack()) {
                    FuelRequisitionApproved::dispatch($reference, Auth::user(), 'sendBack', null);
                } else {
                    FuelRequisitionApproved::dispatch($reference, Auth::user(), 'partiallyAuthorised', null);
                }
            }

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

            $reference = $request->get('reference');

            $requisitionDetail = $this->workshopRequisitionService->getReservationDetail($reference);

            DB::beginTransaction();
            $workflowProcessCode = '';
            switch ($requisitionDetail->item_type) {
                case RequisitionItemTypes::SERVICE:
                case RequisitionItemTypes::NON_STOCK_ITEM:
                    $workflowProcessCode = WorkflowProcessCodes::PurchaseProcess->value;
                    break;
                case RequisitionItemTypes::STOCK_ITEM:
                    $workflowProcessCode = WorkflowProcessCodes::StoresRequisition->value;
                    break;
                default:
                    break;
            }

            $actionTaken = '';
            $message = '';
            $action = 0;
            $userAction = strtolower(trim($request->get('Approved')));
            if ($userAction == 'approve') {
                $action = WorkflowActions::approve();
                $actionTaken = "Approved";
                $message = 'Request Approved Successfully.';
            } elseif ($userAction == 'reject') {
                $action = WorkflowActions::reject();
                $actionTaken = "Rejected";
                $message = 'Request Rejected.';
            } elseif ($userAction == 'send_back') {
                $action = WorkflowActions::sendBack();
                $actionTaken = "SendBack";
                $message = 'Request Sent Back To Originator.';
            }

            list($nextStepId, $nextUser) = $this->workflowService->invokeWorkFlow(
                $reference,
                $workflowProcessCode,
                $action,
                $actionTaken,
                $request->get('Comments')
            );

            if (empty($nextUser)) {
                $nextUser = '';
            }

            if ($nextStepId == 100 && $action == WorkflowActions::approve()) {
                switch ($requisitionDetail->item_type) {
                    case RequisitionItemTypes::SERVICE:
                        $purchaseProcessNumber = $this->workshopRequisitionService
                            ->createWorkshopServicePurchaseProcess(
                                $request->get('reference')
                            );
                        $message = $message
                            . ' Purchase Process No.: ' . $purchaseProcessNumber;
                        break;
                    case RequisitionItemTypes::NON_STOCK_ITEM:
                        $purchaseProcessNumber = $this->workshopRequisitionService
                            ->createWorkshopNonStockPurchaseProcess(
                                $request->get('reference'));
                        $message = $message . ' Purchase Process No.: ' . $purchaseProcessNumber;
                        break;
                    case RequisitionItemTypes::STOCK_ITEM:
                        $reservationNumber = $this->workshopRequisitionService
                            ->createWorkshopMaterialStoresReservation(
                                $request->get('reference'));
                        $message = $message . ' Stores Reservation No.: ' . $reservationNumber;
                        break;
                    default:
                        throw new MaterialReservationException("ITEM TYPE NOT");
                }

                $this->workshopRequisitionService->updateStatus($reference, StatusHelper::authorised());
            } elseif ($nextStepId == 100 && $action == WorkflowActions::reject()) {
                $this->workshopRequisitionService->updateStatus($reference, StatusHelper::rejected());
                $message = 'Request Rejected';
            } else {
                $status = '';
                if (strtolower(trim($request->get('Approved'))) == 'approve') {
                    $message = 'Request Approved and Submitted to the Next Authority For Approval '
                        . $nextUser;
                    $status = StatusHelper::partiallyAuthorised();
                }
                $this->workshopRequisitionService->updateStatus($reference, $status);
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
            if ($e instanceof ServiceRequisitionException
                || $e instanceof WorkflowTaskCreationFailedException
                || $e instanceof MaterialRequisitionException
                || $e instanceof MaterialReservationException
            ) {
                $message = $e->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }
}
