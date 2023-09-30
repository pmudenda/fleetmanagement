<?php

namespace App\Livewire\Mechanic;

use App\Models\Settings\WorkShop;
use App\Models\WorkShopManagement\Mechanic;
use Livewire\Attributes\Rule;
use Livewire\Component;

class WorkshopAdd extends Component
{
    public Mechanic $mechanic;

    #[Rule('required', as: 'Workshop')]
    public $workshop_code;

    #[Rule('required')]
    public $is_supervisor;

    public function render()
    {
        $workshops = WorkShop::all();
        return view('livewire.mechanic.workshop-add',compact('workshops'));
    }
}
