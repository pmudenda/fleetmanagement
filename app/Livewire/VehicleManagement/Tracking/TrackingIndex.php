<?php

namespace App\Livewire\VehicleManagement\Tracking;

use App\Helpers\StatusHelper;
use App\Models\Settings\general\Status;
use App\Models\VehicleManagement\Tracking\Gps;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class TrackingIndex extends Component {
    use WithPagination;

    public $gpses = [];
    public $selectedGps;

    #[Validate('string')]
    public $search;

    public function mount() {
        $this->getLocations();
    }

    public function render() {
//        $this->getLocations();
        $not_connected = Gps::whereNull('connected_at')->count();
        $connected = Gps::whereNotNull('connected_at')->count();
        $total = Gps::count();
        $vehicles = Gps::with('vehicle.roadTax')->orderBy('connected_at', 'asc')->simplePaginate(20);
        return view('livewire.vehicle-management.tracking.tracking-index', compact('not_connected', 'total', 'connected', 'vehicles'));
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
        $this->gpses = Cache::driver('file')->get('gpses',[]);
    }

    public function getLocation($imei): array {
        $gps = Cache::remember("gps-{$imei}",3600, function () use ($imei) {
        return Gps::with(['vehicle.roadTax', 'engineDetail.fuelType'])
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
        $data =  [
            'id' => $gps->id,
            'connected_at' => $gps->connected_at ? $gps->connected_at->diffForHumans(null, true, true) : '--',
            'is_connected' => $gps->connected_at != null,
            'imei' => $gps->imei,
            'reg' => $gps->reg_number,
            'brand' => "{$gps->vehicle->brand_name} {$gps->vehicle->model_name}",
            'business_unit' => $gps->vehicle->business_unit_name ?? '--',
            'fuel_type' => $gps->engineDetail->fuelType->description ?? '--',
            'status' => $statuses->where('code', $gps->vehicle->status)->first()->name ?? '--',
            'fitness' => isset($gps->vehicle->roadTax->fitness_expiry) ? $gps->vehicle->roadTax->fitness_expiry->toFormattedDateString() : '--',
            'road_tax' => isset($gps->vehicle->roadTax->valid_to) ? $gps->vehicle->roadTax->valid_to->toFormattedDateString() : '--',
            'rtsa_status' => $gps->vehicle->roadTax->status ?? '--',
            'is_compliant' => $gps->vehicle->roadTax->is_compliant ?? false
        ];
        $existingData = Cache::driver('file')->get('gpses',[]) ;
        $existingData[$gps->imei] = $data;
//        dd($existingData);
        Cache::driver('file')->put('gpses', $existingData, now()->addHours(2));

        return $data;
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
