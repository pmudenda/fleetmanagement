<?php

namespace App\Livewire\VehicleManagement\Tracking;

use App\Models\VehicleManagement\Tracking\Gps;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class GpsDeviceDetails extends Component {
    public Gps $gps;
    public $location = [];

    public function render() {
        $vehicleStates = [
            '01' => ['class' => 'bg-success', 'icon' => 'fa-check-circle'],      // ACTIVE
            '02' => ['class' => 'bg-secondary', 'icon' => 'fa-ban'],               // INACTIVE
            '03' => ['class' => 'bg-info', 'icon' => 'fa-handshake'],          // HANDED OVER
            '04' => ['class' => 'bg-warning text-dark', 'icon' => 'fa-anchor'],    // GROUNDED
            '05' => ['class' => 'bg-warning text-dark', 'icon' => 'fa-tools'],     // VEHICLE IN WORKSHOP
            '06' => ['class' => 'bg-dark', 'icon' => 'fa-trash'],              // SCRAP
            '07' => ['class' => 'bg-danger', 'icon' => 'fa-user-slash'],         // STOLEN
            '08' => ['class' => 'bg-danger', 'icon' => 'fa-gavel'],              // DISPOSED / SOLD
            '09' => ['class' => 'bg-warning text-dark', 'icon' => 'fa-hourglass-half'], // PENDING DISPOSAL
            '10' => ['class' => 'bg-info', 'icon' => 'fa-recycle'],            // SALVAGE
            '11' => ['class' => 'bg-primary', 'icon' => 'fa-id-card'],            // RE-REGISTERED
        ];

        $stateCode = $this->gps->vehicle->state->code ?? null;
        $stateMeta = $vehicleStates[$stateCode] ?? ['class' => 'bg-light text-dark', 'icon' => 'fa-question-circle'];
        return view('livewire.vehicle-management.tracking.gps-device-details', compact('stateMeta'));
    }

    #[On('device-selected')]
    public function deviceSelected($location, Gps $gps) {
        $gps->loadMissing('vehicle.roadTax', 'vehicle.engine');
        if($location){
            $location['tracked_at'] = Carbon::parseFromLocale($location['tracked_at'])->diffForHumans();
        }
        $this->fill(compact('location', 'gps'));
    }

    #[On('location-changed')]
    public function locationChanged($location) {

        if(!isset($this->gps)){
            $this->skipRender();
            return;
        }

        if($location['imei'] == $this->gps->imei){
            $location['tracked_at'] = Carbon::parseFromLocale($location['tracked_at'])->diffForHumans();
            $this->fill(compact('location'));

        }
    }


}
