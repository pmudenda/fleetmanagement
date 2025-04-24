<?php

namespace App\Livewire\VehicleManagement\Tracking;

use App\Helpers\StatusHelper;
use App\Models\Settings\general\Status;
use App\Models\VehicleManagement\Tracking\Gps;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TrackingIndex extends Component {
    public $gpses;
    public $selectedGps;

    #[Validate('string')]
    public $search;

    public function mount() {
//        $this->getLocations();
    }

    public function render() {
        $this->getLocations();
        $not_connected = Gps::whereNull('connected_at')->count();
        $connected = Gps::whereNotNull('connected_at')->count();
        $total = Gps::count();
        return view('livewire.vehicle-management.tracking.tracking-index', compact('not_connected', 'total', 'connected'));
    }

    public function refresh() {
        $this->getLocations();
        $this->render();
    }

    public function select(Gps $gps) {
        $this->selectedGps = $gps;
        $path = $gps->locations()->where('tracked_at', '>=', $gps->lastLocation->tracked_at->subHour())
            ->oldest()
            ->get();
        $this->dispatch('gps-selected', gps: $gps, paths: $path->map(fn($item) => [
            'lat' => (float)$item->latitude,
            'lng' => (float)$item->longitude,
        ]));
    }

    /**
     * @return void
     */
    public function getLocations(): void {
        $this->gpses = Gps::with(['lastLocation', 'vehicle', 'engineDetail.fuelType'])
            ->has('lastLocation')
            ->when($this->search, function ($query, $search) {
                $query->where('reg_number', 'like', '%' . $search . '%');
            })
//                ->orderBy('connected_at', 'DESC')
            ->get()->map(function ($gps) {
                return $this->getGpsDetails($gps);
            });
    }

    public function getLocation($imei): array {
        $gps = Cache::rememberForever("gps-{$imei}", function () use ($imei) {
            return Gps::with(['vehicle', 'engineDetail.fuelType'])
                ->where('imei', $imei)
//            ->whereNotNull('connected_at')
                ->orderBy('connected_at', 'DESC')
                ->first();
        });

        if (is_null($gps)) {
            return [];
        }
        return $this->getGpsDetails($gps);
    }

    private function getGpsDetails($gps): array {
        $statuses = Status::all();
        return [
            'id' => $gps->id,
            'connected_at' => $gps->connected_at ? $gps->connected_at->diffForHumans(null, true, true) : null,
            'is_connected' => $gps->connected_at != null,
            'imei' => $gps->imei,
            'reg' => $gps->reg_number,
            'brand' => "{$gps->vehicle->brand_name} {$gps->vehicle->model_name}",
            'business_unit' => $gps->vehicle->business_unit_name ?? null,
            'fuel_type' => $gps->engineDetail->fuelType->description ?? null,
            'status' => $statuses->where('code', $gps->vehicle->status)->first()->name ?? null,
        ];
    }

//    #[On('echo:gps.location,CurrentLocationEvent')]
//    public function locationUpdate($location) {
//        $location = $location['location'];
//        $gps = $this->getLocation($location['imei']);
//        $details = array_merge($gps, $location);
//
//        $this->gpses = collect($this->gpses)->map(function ($item) use ($details) {
//            return $item['imei'] === $details['imei'] ?? null
//                ? array_merge($item, $details)
//                : $item;
//        });
//
////        if ($details) {
////            $this->dispatch('location-update', location: $details);
////        }
//    }
}
