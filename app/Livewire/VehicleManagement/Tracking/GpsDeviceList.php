<?php

namespace App\Livewire\VehicleManagement\Tracking;

use App\Models\VehicleManagement\Tracking\Gps;
use Livewire\Component;
use Livewire\WithPagination;

class GpsDeviceList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selectedGps;

    public function render()
    {
        $devices = Gps::with('vehicle.roadTax')->orderBy('connected_at', 'asc')
            ->get();
        return view('livewire.vehicle-management.tracking.gps-device-list',compact('devices'));
    }


    public function deviceSelected(Gps $gps, $location) {
        $this->selectedGps = $gps;
    }
}
