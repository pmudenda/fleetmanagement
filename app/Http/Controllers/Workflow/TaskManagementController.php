<?php

namespace App\Http\Controllers\Workflow;

use App\Helpers\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Request;
use App\Services\Workflow\WorkflowService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class TaskManagementController extends Controller
{
    private WorkflowService $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function list(): View
    {
        $approvalTasks = $this->workflowService->getAllWorkflowTasks();

        return view('modules.workflow.tasks')
            ->with(compact('approvalTasks'));
    }

    public function json(Request $request): JsonResponse
    {
        $approvalTasks = $this->workflowService->getAllWorkflowTasks();

        $columns = array(
            array('db' => 'url', 'dt' => 0),
            array('db' => 'reference', 'dt' => 1),
            array('db' => 'subject', 'dt' => 2),
            array('db' => 'description', 'dt' => 3),
            array('db' => 'approver', 'dt' => 3),
            array('db' => 'originator', 'dt' => 3),
            array('db' => 'originator', 'dt' => 3),
            array(
                'db' => 'date_acted',
                'dt' => 4,
                'formatter' => function ($d, $row) {
                    return Carbon::parse($d)->format('d/m/Y');
                }
            ),
            array(
                'db' => 'salary',
                'dt' => 5,
                'formatter' => function ($d, $row) {
                    return '$' . number_format($d);
                }
            )
        );


        return response()->json(
            DataTables::simple($request, '', $columns)
        );
    }
}
