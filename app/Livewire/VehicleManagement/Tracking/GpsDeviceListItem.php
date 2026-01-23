<?php

namespace App\Livewire\VehicleManagement\Tracking;

use App\Helpers\VehicleStatus;
use App\Models\VehicleManagement\Tracking\Gps;
use Illuminate\Support\Facades\Redis;
use Livewire\Component;

class GpsDeviceListItem extends Component {
    public Gps $gps;
    public array $location = [];

    // UI signals for the left list
    public string $severity = 'gray';     // red | amber | green | gray
    public string $signalText = 'Unknown';

    public function mount(Gps $gps): void {
        $this->gps = $gps;

        $raw = Redis::client()->get("last-location-{$gps->imei}");
        $this->location = $raw ? json_decode($raw, true) : [];

        if ($this->location) {
            $this->dispatch("location-changed", location: $this->location);
        }
    }

    public function render() {
      $icons = [];
      if($this->location) {
          $icons = $this->location['signals']['icons'];
      }
        return view('livewire.vehicle-management.tracking.gps-device-list-item',compact('icons'));
    }

    public function locationReceived($event): void {
        $this->location = $event['location'] ?? [];
        $icons = $this->location['signals']['icons'] ?? [];
        $this->dispatch('location-changed.{$this->gps->imei}', location: $this->location);
    }

    private function computeSignals(): void {
        if (!$this->gps) {
            $this->severity = 'gray';
            $this->signalText = 'Unknown';
            return;
        }

        $speed = (float)data_get($this->location, 'speed', 0);

        // You currently have roadTax relation on GPS (Gps::with('vehicle.roadTax') in list)
        // If it is actually on vehicle, swap to optional($this->gps->vehicle->roadTax).
        $isCompliant = optional($this->gps->roadTax)->is_compliant ?? false;
        $rtsaInfraction = ($isCompliant === false);

        // Maintenance flag (your existing logic)
        $inMaintenance = optional($this->gps->vehicle)->status == VehicleStatus::vehicleInWorkshop();
        $isMoving = $speed > 5;

        $overspeed = $speed > 100;
        $maintenanceMoving = $inMaintenance && $isMoving;

        // Worst-state-wins (signal-driven)
        if ($rtsaInfraction) {
            $this->severity = 'red';
            $this->signalText = 'RTSA non-compliant';
        } elseif ($overspeed) {
            $this->severity = 'red';
            $this->signalText = "Speeding {$speed} km/h";
        } elseif ($maintenanceMoving) {
            $this->severity = 'amber';
            $this->signalText = 'Moving in maintenance';
        } elseif (!$isMoving) {
            $this->severity = 'gray';
            $this->signalText = 'Idle';
        } else {
            $this->severity = 'green';
            $this->signalText = 'Moving';
        }

        // Optional: attach flags so the UI / other components can reuse
        $this->location['flags'] = [
            'rtsa_infraction' => $rtsaInfraction,
            'overspeed' => $overspeed,
            'maintenance_moving' => $maintenanceMoving,
            'moving' => $isMoving,
        ];
    }

    public function gpsSelected(): void {
        $this->dispatch('device-selected', location: $this->location, gps: $this->gps->imei);
    }
}
