<?php

namespace App\View\Components;

use App\Constants\QueryComparisonOperator;
use App\Constants\TableColumns;
use App\Models\Security\User;
use App\Models\Workflow\WorkflowLog;
use App\Models\Workflow\WorkflowTaskDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class WorkshopWorkflowApprovers extends Component
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
    public function render(): View|string
    {
        $documentStatus = $this->request->status;
        $approvalsArray = WorkflowLog::where(
            'reference',
            $this->task->reference)
            ->orderBy('action_date')
            ->get();

        $steps = $approvalsArray->pluck('step_id')->toArray();
        $currentStep = WorkflowTaskDetail::where(
            'reference',
            QueryComparisonOperator::EQUALS,
            $this->task->reference)
            ->first();

//        dd($this->request->created_by);
        $claimant = User::where(
            "ID",
            QueryComparisonOperator::EQUALS,
            $this->request->created_by
        )->first();


        Log::info($claimant);

        $supervisor = null;
        if (!empty($claimant)) {
            $supervisor = User::where(
                TableColumns::STAFF_NUMBER,
                QueryComparisonOperator::EQUALS,
                $claimant->supervisor_code)->first();
        }

        $manager = null;

        if (!empty($supervisor) && ($currentStep->current_step_id == '03'
                || in_array('03', $steps))) {
            $manager = User::where(
                TableColumns::STAFF_NUMBER,
                QueryComparisonOperator::EQUALS,
                $supervisor->supervisor_code)->first();
        }

        $snrManager = null;
        $deputyDirector = null;
        $director = null;
        $managingDirector = null;

        return view(
            'components.workshop-workflow-approvers',
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
