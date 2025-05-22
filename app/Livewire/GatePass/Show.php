<?php

namespace App\Livewire\GatePass;

use App\Enums\GatePassStatus;
use App\Models\GatePass\GatePass;
use App\Models\Security\EmployeeApprovers;
use App\Models\Security\User;
use App\Models\Settings\general\Status;
use Livewire\Component;

class Show extends Component {
    public GatePass $gatePass;
    public $reason;

    protected $rules = [
        'reason' => 'required|min:5|string|max:255',
    ];


    public function render() {
        $vehicle = $this->gatePass->vehicle;
        $statuses = Status::all();
        $status = $statuses->where('code', $vehicle->status)->first()->name;
        $user = $this->gatePass->user;


        $approvers = EmployeeApprovers::where('business_unit_code', $user->bu_code)
            ->where('cost_center_code', $user->cc_code)
            ->where('role_id', 70)
            ->get();

        $authorisers = User::permission(['gatepass_authorise_out_of_town', 'gatepass_authorise_local'])
            ->whereIn('staff_no', $approvers->pluck('con_per_no')->toArray())
            ->get();

        $is_rejected = $this->gatePass->status == GatePassStatus::REJECTED && $this->gatePass->authorised_by;

        return view('livewire.gate-pass.show', compact('vehicle', 'status', 'user', 'authorisers','is_rejected'));
    }

    public function approve() {
        $this->validate();
        $this->gatePass->update([
            'authorised_at' => now(),
            'authorised_by' => auth()->user()->id,
            'status' => GatePassStatus::AUTHORIZED,
            'authorised_reason' => $this->reason,
        ]);
        $this->dispatch('message', 'Gate Pass Approved Successfully');

    }

    public function reject() {
        $this->validate();

        $this->gatePass->update([
            'authorised_at' => now(),
            'authorised_by' => auth()->user()->id,
            'status' => GatePassStatus::REJECTED,
            'authorised_reason' => $this->reason,
        ]);
        $this->dispatch('message', 'Gate Pass Rejected Successfully');

    }
}
