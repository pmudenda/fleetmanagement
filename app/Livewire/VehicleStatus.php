<?php

namespace App\Livewire;

use Livewire\Component;

class VehicleStatus extends Component {
    public $vehicle;

    public function render() {
        if ($this->vehicle) {
            dd($this->vehicle);
        }
        return view('livewire.vehicle-status');
    }
}
