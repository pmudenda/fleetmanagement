<?php

namespace App\Livewire\VehicleManagement\Tracking;

use App\Models\VehicleManagement\Tracking\Gps;
use Livewire\Component;

class GpsDeviceListItem extends Component
{
    public Gps $gps;

    public function render()
    {
        return view('livewire.vehicle-management.tracking.gps-device-list-item');
    }
}
