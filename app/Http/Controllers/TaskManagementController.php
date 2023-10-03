<?php

namespace App\Http\Controllers;

use App\Services\Workflow\WorkflowService;
use Illuminate\Contracts\View\View;

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
}
