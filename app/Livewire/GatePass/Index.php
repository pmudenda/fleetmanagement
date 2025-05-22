<?php

namespace App\Livewire\GatePass;

use App\Models\GatePass\GatePass;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public function render()
    {
        $gatePasses = GatePass::latest()->where('user_id', auth()->id())->paginate(10);
        return view('livewire.gate-pass.index',compact('gatePasses'));
    }
}
