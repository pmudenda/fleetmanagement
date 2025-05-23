<?php

namespace App\Livewire\GatePass;

use App\Models\GatePass\GatePass;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class AllIndex extends Component
{
    use WithPagination;

    public $search;

    protected $rules = [
        'search' => 'required|string'
    ];

    public function render()
    {
        $gatePasses = GatePass::when($this->search, function (Builder $query) {
            $query->where('reference_number', 'like', "{$this->search}%");
            $query->orWhere('reg_no', 'like', "{$this->search}%");
            $query->orWhereRelation('user','name', 'like', "%{$this->search}%");
            $query->orWhereRelation('user','staff_no', 'like', "%{$this->search}%");
            $query->orWhereRelation('authorisedBy','name', 'like', "%{$this->search}%");
            $query->orWhereRelation('checkedBy','name', 'like', "%{$this->search}%");
        })
            ->latest()
            ->paginate(10);
        return view('livewire.gate-pass.all-index',compact('gatePasses'));
    }

}
