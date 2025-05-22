<?php

namespace App\Livewire\GatePass;

use App\Enums\GatePassStatus;
use App\Models\GatePass\GatePass;
use App\Models\Security\EmployeeApprovers;
use App\Models\Security\User;
use Livewire\Component;
use Livewire\WithPagination;

class UncheckedIndex extends Component
{
    use WithPagination;

    public function mount() {

    }

    public function render()
    {
        $gatePasses = GatePass::latest()->where('status', GatePassStatus::AUTHORIZED)->paginate(10);
        return view('livewire.gate-pass.unchecked-index',compact('gatePasses'));
    }
}
