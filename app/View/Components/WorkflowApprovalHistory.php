<?php

namespace App\View\Components;

use Illuminate\View\Component;

class WorkflowApprovalHistory extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $approvals;
    public $request;

    public function __construct($approvals, $request)
    {
        $this->approvals = $approvals;
        $this->request = $request;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.workflow-approval-history');
    }
}
