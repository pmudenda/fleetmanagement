<?php

namespace App\Livewire\Mechanic;

use App\Models\WorkShopManagement\Mechanic;
use Livewire\Component;

class WorkshopIndex extends Component
{

    public  $mechanic;

    public function mount(){
        $this->mechanic = Mechanic::find($this->mechanic);
    }
    public function render()
    {
        $workshops = $this->mechanic->workshops()->paginate(10);
        return view('livewire.mechanic.workshop-index',compact('workshops'));
    }
}
