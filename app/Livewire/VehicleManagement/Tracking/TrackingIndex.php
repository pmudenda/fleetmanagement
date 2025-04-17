<?php

namespace App\Livewire\VehicleManagement\Tracking;

use App\Helpers\StatusHelper;
use App\Models\Settings\general\Status;
use App\Models\VehicleManagement\Tracking\Gps;
use Livewire\Component;

class TrackingIndex extends Component {
    public $gpses;

    public function render() {
        $statuses = Status::all();
        $this->gpses = Gps::with(['lastLocation','vehicle','engineDetail.fuelType'])->whereNotNull('connected_at')->get()->map(function ($gps) use ($statuses) {

            return [
                'lat' => (float)$gps->lastLocation->latitude ?? null,
                'lng' => (float)$gps->lastLocation->longitude ?? null,
                'connected_at' => $gps->connected_at ? $gps->connected_at->diffForHumans() : null,
                'imei' => $gps->imei,
                'reg' => $gps->reg_number,
                'speed' => $gps->lastLocation->speed,
                'brand' => "{$gps->vehicle->brand_name} {$gps->vehicle->model_name}",
                'business_unit' => $gps->vehicle->business_unit_name ?? null,
                'fuel_type' => $gps->engineDetail->fuelType->description ?? null,
                'status' => $statuses->where('code', $gps->vehicle->status)->first()->name ?? null,
            ];
        });
        return view('livewire.vehicle-management.tracking.tracking-index');
    }
}
