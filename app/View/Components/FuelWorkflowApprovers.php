<?php

namespace App\View\Components;

use App\Constants\QueryComparisonOperator;
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
        $approvalsArray = WorkflowLog::where('reference', $this->task->reference)
            ->orderBy('action_date')
            ->get();

        $steps = $approvalsArray->pluck('step_id')->toArray();
        $currentStep = WorkflowTaskDetail::where('reference', '=', $this->task->reference)
            ->first();

        $claimant = User::where('staff_no', '=', $this->request->created_by)->first();

        //$supervisor = User::where('staff_no', '=', $claimant->supervisor_code)->first();

        if (in_array('02', $steps)) {
            $actionedUser = WorkflowLog::where('reference', $this->task->reference)
                ->where('step_id', QueryComparisonOperator::EQUALS, '02')
                ->orderBy('action_date')
                ->first();
            $supervisor = User::where('staff_no', '=', $actionedUser->actioning_officer)->first();
        } else {
            $supervisor = User::where('staff_no', '=', $claimant->supervisor_code)->first();
        }


        $manager = null;

        if (!empty($supervisor) && ($currentStep->current_step_id == '03' || in_array('03', $steps))) {
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
                'approvalsArray',
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
