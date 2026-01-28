<?php

namespace App\Livewire\VehicleManagement\Tracking;

use App\Models\VehicleManagement\Tracking\Gps;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;

class RouteHistory extends Component
{
    public Gps  $gps;

    #[Url]
    public ?string $from = null;

    #[Url]
    public ?string $to = null;

    public array $points = [];
    public bool $loaded = false;

    /**
     * Route param is IMEI string (not route-model binding).
     */
    public function mount(Gps $gps): void
    {
        $now = Carbon::now()->subDay();
        $this->from ??= $now->copy()->startOfDay()->format('Y-m-d\TH:i');
        $this->to   ??= $now->format('Y-m-d\TH:i');
    }

    public function loadRoute(): void
    {
        $this->validate([
            'from' => ['required', 'date'],
            'to'   => ['required', 'date', 'after:from'],
        ]);

        $from = Carbon::parse($this->from);
        $to   = Carbon::parse($this->to);
        $rows = $this->gps->locations()
            // NOTE: Your column names look Oracle-style. If your Eloquent model uses snake_case
            // columns, change these to tracked_at/latitude/longitude accordingly.
            ->whereBetween('tracked_at', [$from, $to])
//            ->whereNotNull('LATITUDE')
//            ->whereNotNull('LONGITUDE')
            ->orderBy('tracked_at')
            ->limit(20000)
            ->get();

        $points = $rows->map(function ($r) {
            // Supports either raw Oracle column names OR accessor properties on your model
            $lat = $r->latitude;
            $lng = $r->longitude;

            return [
                'lat'       => (float) $lat,
                'lng'       => (float) $lng,
                'trackedAt' => (string) ($r->tracked_at ?? $r->TRACKED_AT ?? ''),
                'speed'     => isset($r->speed) ? (float) $r->speed : (isset($r->SPEED) ? (float) $r->SPEED : null),
                'angle'     => is_null($r->angle ?? ($r->ANGLE ?? null)) ? null : (float) ($r->angle ?? $r->ANGLE),
                'igni   tion'  => isset($r->ignition) ? (int) $r->ignition : (isset($r->IGNITION) ? (int) $r->IGNITION : null),
                'odometer'  => isset($r->odometer) ? (float) $r->odometer : (isset($r->ODOMETER) ? (float) $r->ODOMETER : null),
                'fuel'      => isset($r->fuel) ? (float) $r->fuel : (isset($r->FUEL) ? (float) $r->FUEL : null),
                // Optional for label:
                'reg_number' => $this->gps->vehicle->reg_number ?? $this->gps->vehicle->registration ?? null,
            ];
        })->values()->all();

        $this->loaded = true;

        $this->dispatch('route-loaded', points: $points);
    }

    public function render()
    {
        return view('livewire.vehicle-management.tracking.route-history');
    }
}
