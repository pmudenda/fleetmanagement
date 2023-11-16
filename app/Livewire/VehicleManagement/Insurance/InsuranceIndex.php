<?php

namespace App\Livewire\VehicleManagement\Insurance;

use App\Models\VehicleManagement\Insurance;
use Livewire\Component;
use Livewire\WithPagination;

class InsuranceIndex extends Component
{
    use WithPagination;

    public function render()
    {
        $insurances = Insurance::paginate(10);
        return view('livewire.vehicle-management.insurance.insurance-index',compact('insurances'));
    }
}
