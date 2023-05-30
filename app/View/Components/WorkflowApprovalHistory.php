<?php

namespace App\View\Components;

use App\Models\Workflow\WorkflowLog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
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
     * @return View|\Closure|string
     */
    public function render(): View|string|\Closure
    {
        $this->approvals = WorkflowLog::where('reference', '=', $this->request->req_no)->get();
        Log::info($this->approvals);
        return view('components.workflow-approval-history');
    }
}
