<?php

namespace App\View\Components;

use App\Enums\ConfigurationTypes;
use App\Enums\Modules;
use App\Models\Workflow\WorkflowModules;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
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
        $this->approvals = DB::table("WFL_WORKFLOW_LOGS")
            ->where('reference', '=', $this->request->req_no)
            ->join('SEC_USERS', 'WFL_WORKFLOW_LOGS.actioning_officer', '=', 'SEC_USERS.staff_no')
            ->leftJoin('CONFIG_STATUSES', 'WFL_WORKFLOW_LOGS.status', '=', 'CONFIG_STATUSES.code')
            ->leftJoin('WFL_WORKFLOW_ACTIONS', 'WFL_WORKFLOW_LOGS.action', '=', 'WFL_WORKFLOW_ACTIONS.action_id')
            ->where('CONFIG_STATUSES.module', '=', Modules::Material)
            ->select(
                'WFL_WORKFLOW_LOGS.*',
                'WFL_WORKFLOW_ACTIONS.name as action_name',
                'WFL_WORKFLOW_ACTIONS.description as action_description',
                'CONFIG_STATUSES.name as status_name',
                'SEC_USERS.name','SEC_USERS.avatar')
            ->get();
        //Log::info($this->approvals);
        return view('components.workflow-approval-history');
    }
}
