<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Services\Requisitions\FuelRequisitionService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class WorkflowController extends Controller
{
    private FuelRequisitionService $requisitionService;

    public function __construct(FuelRequisitionService $requisitionService)
    {
        $this->requisitionService = $requisitionService;
    }

    public function showWorkTask(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $workTask = WorkflowTaskHeader:: orderBy('created_at', 'ASC')->get();
        return view('modules.workflow.approvals', compact(['workTask']))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function processFuelRequisitionApproval(Request $request): JsonResponse
    {
        if ($request->get('docType') == 'FuelRequisition') {
            //$request->get('Comments')
            //$request->get('reference')
            //$request->get('Approved')
            $this->requisitionService->processFuelRequisitionApproval(
                $request->get('reference'),
                $request->get('Approved'),
                $request->get('Comments'));
        }

        return response()->json([
            'requestPayload' => $request->all(),
            'success' => true,
            'redirectUrl' => route('list.fuel.requisition'),
            'message' => 'Request Approved Successfully'
        ]);
    }
}
