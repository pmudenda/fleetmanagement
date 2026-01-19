<?php

namespace App\Livewire\VehicleManagement\Tracking;

use App\Models\VehicleManagement\Tracking\Gps;
use Livewire\Component;

class GpsDeviceList extends Component
{
    public function render()
    {
        $gpses = Gps::with(['vehicle.engine','vehicle.roadTax'])
            ->paginate(10);
        return view('livewire.vehicle-management.tracking.gps-device-list',compact('gpses'));
    }
}
