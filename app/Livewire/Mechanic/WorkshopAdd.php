<?php

namespace App\Livewire\Mechanic;

use App\Enums\IsSupervisor;
use App\Models\Settings\WorkShop;
use App\Models\WorkShopManagement\Mechanic;
use Livewire\Attributes\Rule;
use Livewire\Component;

class WorkshopAdd extends Component {
    public Mechanic $mechanic;

    #[Rule('required', as: 'Workshop')]
    public $workshop_code;

    #[Rule('required')]
    public $is_supervisor;

    public function render() {
        $workshops = WorkShop::whereNotIn('workshop_code', $this->mechanic->workshops()->get()->pluck('workshop_code')->values()->toArray())->get();
        $supervisors = IsSupervisor::asSelectArray();
        return view('livewire.mechanic.workshop-add', compact('workshops', 'supervisors'));
    }

    public function save() {
        $this->validate();

        $this->mechanic->workshops()->attach($this->workshop_code, ['is_superpvisor' => $this->is_supervisor]);
        $this->dispatch('modal-close','close');
        $this->dispatch('update');
        $this->dispatch('message', 'Workshop added successfully');
        $this->reset('workshop_code','is_supervisor');
    }
}
