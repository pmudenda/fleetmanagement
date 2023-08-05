<?php

namespace App\View\Components;

use App\Models\Security\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FuelWorkflowApprovers extends Component
{

    public $request;

    /**
     * Create a new component instance.
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $claimant = User::where('staff_no', '=', $this->request->created_by)->first();
        $supervisor = User::where('staff_no', '=', $claimant->supervisor_code)->first();

        /*$form_grade = $this->request->grade;
        $form_details = $this->request;

        $approvals_array = WorkflowLog::where('reference', $this->request->reference)
            ->pluck('action')->unique()->toArray();

        //CLAIMANT
        $hod_unit_user = $this->request->claimantUserUnit->hod_unit_user;
        $hod_unit_delegate_user = $this->request->claimantUserUnit->hod_unit_delegate_user;
        $hod_unit_users = $hod_unit_user->merge($hod_unit_delegate_user);

        $dm_unit_user = $this->request->claimantUserUnit->dm_unit_user;
        $dm_unit_delegate_user = $this->request->claimantUserUnit->dm_unit_delegate_user;
        $dm_unit_users = $dm_unit_user->merge($dm_unit_delegate_user);

        $hrm_unit_user = $this->request->claimantUserUnit->hrm_unit_user;
        $hrm_unit_delegate_user = $this->request->claimantUserUnit->hrm_unit_delegate_user;
        $hrm_unit_users = $hrm_unit_user->merge($hrm_unit_delegate_user);

        $dr_unit_user = $this->request->claimantUserUnit->dr_unit_user;
        $dr_unit_delegate_user = $this->request->claimantUserUnit->dr_unit_delegate_user;
        $dr_unit_users = $dr_unit_user->merge($dr_unit_delegate_user);

        // BUDGET HOLDER
        $ca_unit_user = $this->request->user_unit->ca_unit_user;
        $ca_unit_delegate_user = $this->request->user_unit->ca_unit_delegate_user;
        $ca_unit_users = $ca_unit_user->merge($ca_unit_delegate_user);

        $expenditure_unit_user = $this->request->user_unit->expenditure_unit_user;
        $expediture_unit_delegate_user = $this->request->user_unit->expenditure_unit_delegate_user;
        $expenditure_unit_users = $expenditure_unit_user->merge($expediture_unit_delegate_user);*/

        return view(
            'components.fuel-workflow-approvers',
            compact('claimant', 'supervisor')
        );
    }
}
