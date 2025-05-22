<?php

namespace App\Livewire\GatePass;

use App\Enums\GatePassStatus;
use App\Models\GatePass\GatePass;
use App\Models\Security\EmployeeApprovers;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class UnauthorisedIndex extends Component {
    use WithPagination;

    public function render() {

        $user = auth()->user();
        $approvers = EmployeeApprovers::where('con_per_no', $user->staff_no)
            ->where('role_id', 70)
            ->get();

        $gatePasses = GatePass::latest()->whereHas('user',function (Builder $query) use ($approvers, $user) {
            $query->whereIn('bu_code', $approvers->pluck('business_unit_code'));
            $query->whereIn('cc_code', $approvers->pluck('cost_center_code'));
        })
            ->where('status', GatePassStatus::NEW)
            ->paginate(10);

        return view('livewire.gate-pass.unauthorised-index', compact('gatePasses'));
    }
}
