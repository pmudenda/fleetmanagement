<?php

namespace App\Livewire;

use App\Models\VehicleManagement\VehicleHeader;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class VehicleStatus extends Component {
    public $vehicle, $verified;

    protected $rules = [
        'verified' => 'required'
    ];

    public function mount(){
        $this->verified = $this->vehicle->verified ?? null;
    }

    public function render() {
        return view('livewire.vehicle-status');
    }

    public function save(){
        $this->validate();
        $this->vehicle->verified = $this->verified;
        $car = VehicleHeader::find($this->vehicle->headerid);
        $car->verified = $this->verified;
        $car->save();

    }
}
