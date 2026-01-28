<?php

namespace App\Livewire\VehicleManagement\Tracking;

use App\Models\VehicleManagement\Tracking\Gps;
use App\Models\VehicleManagement\VehicleHeader;
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
    public VehicleHeader $vehicle;
    public $currentPoint;

    /**
     * Route param is IMEI string (not route-model binding).
     */
    public function mount(Gps $gps): void
    {
        $this->vehicle = $gps->vehicle;
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
                'lat'        => (float) $lat,
                'lng'        => (float) $lng,
                'trackedAt'  => (string) $r->tracked_at->toDayDateTimeString(),
                'speed'      => (float) $r->speed,
                'angle'      => (float) $r->angle,
                'ignition'   => (int) $r->ignition,
                'odometer'   => (float) $r->odometer,
                'fuel'       => (float) $r->fuel,
                'reg_number' => $this->gps->vehicle->reg_number,
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
