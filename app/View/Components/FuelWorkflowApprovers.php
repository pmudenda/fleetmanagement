<?php

namespace App\View\Components;

use App\Models\Security\User;
use App\Models\Workflow\WorkflowLog;
use App\Models\Workflow\WorkflowTaskDetail;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FuelWorkflowApprovers extends Component
{

    public $request;
    public $task;

    /**
     * Create a new component instance.
     */
    public function __construct($request, $task)
    {
        $this->request = $request;
        $this->task = $task;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $documentStatus = $this->request->status;
        $approvals_array = WorkflowLog::where('reference', $this->task->reference)
            ->orderBy('action_date')
            ->get();

        $steps = $approvals_array->pluck('step_id')->toArray();
        $currentStep = WorkflowTaskDetail::where('reference', $this->task->reference)->first();

        $claimant = User::where('staff_no', '=', $this->request->created_by)->first();
        $supervisor = User::where('staff_no', '=', $claimant->supervisor_code)->first();
        $manager = null;

        if (!empty($supervisor)) {
            $manager = User::where('staff_no', '=', $supervisor->supervisor_code)->first();
        }

        $snrManager = null;
        $deputyDirector = null;
        $director = null;
        $managingDirector = null;

        return view(
            'components.fuel-workflow-approvers',
            compact('documentStatus',
                'steps',
                'currentStep',
                'approvals_array',
                'claimant',
                'supervisor',
                'manager',
                'snrManager',
                'deputyDirector',
                'director',
                'managingDirector'
            )
        );
    }
}
