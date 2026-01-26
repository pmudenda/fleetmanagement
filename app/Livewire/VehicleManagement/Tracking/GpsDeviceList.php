<?php

namespace App\Livewire\VehicleManagement\Tracking;

use App\Events\Tracking\CurrentLocationEvent;
use App\Models\VehicleManagement\Tracking\Gps;
use Illuminate\Support\Facades\Redis;
use Livewire\Component;
use Livewire\WithPagination;

class GpsDeviceList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selectedGps;

    public function render()
    {
        $devices = Gps::with('vehicle.roadTax')
            ->orderByRaw('last_seen_at DESC NULLS LAST')
            ->orderby('reg_number','ASC')
            ->get();

        return view('livewire.vehicle-management.tracking.gps-device-list',compact('devices'));
    }


    public function deviceSelected(Gps $gps, $location) {
        $this->selectedGps = $gps;
    }
}
