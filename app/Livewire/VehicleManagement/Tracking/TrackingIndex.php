<?php

namespace App\Livewire\VehicleManagement\Tracking;

use App\Helpers\StatusHelper;
use App\Models\Settings\general\Status;
use App\Models\VehicleManagement\Tracking\Gps;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TrackingIndex extends Component {
    public $gpses;
    public $selectedGps;

    #[Validate('string')]
    public $search;

    public function mount() {
        $this->getLocations();
    }

    public function render() {
        $this->getLocations();
        $not_connected = Gps::whereNull('connected_at')->count();
        $connected = Gps::whereNotNull('connected_at')->count();
        $total = Gps::count();
        return view('livewire.vehicle-management.tracking.tracking-index',compact('not_connected','total','connected'));
    }

    public function refresh() {
        $this->getLocations();
        $this->render();
    }

    public function select(Gps $gps) {
        $this->selectedGps = $gps;
        $path = $gps->locations()->where('tracked_at', '>=', $gps->lastLocation->tracked_at->subSeconds(30))
            ->oldest()
            ->get();
        $this->dispatch('gps-selected',gps: $gps, paths: $path->map(fn ($item) => [
            'lat' => (float)$item->latitude,
            'lng' => (float)$item->longitude,
        ]));
    }

    /**
     * @return void
     */
    public function getLocations(): void {
        $statuses = Status::all();
        $this->gpses = Gps::with(['lastLocation', 'vehicle', 'engineDetail.fuelType'])
            ->has('lastLocation')
            ->when($this->search, function ($query, $search) {
                $query->where('reg_number', 'like', '%' . $search . '%');
//                $query->orWhere('imei', 'like', '%' . $search . '%');
            })
//            ->whereNotNull('connected_at')
                ->orderBy('connected_at', 'DESC')
            ->get()->map(function ($gps) use ($statuses) {
            return [
                'id' => $gps->id,
                'lat' => (float)$gps->lastLocation->latitude ?? null,
                'lng' => (float)$gps->lastLocation->longitude ?? null,
                'connected_at' => $gps->connected_at ? $gps->connected_at->diffForHumans(null, true, true) : null,
                'is_connected' => $gps->connected_at != null,
                'imei' => $gps->imei,
                'reg' => $gps->reg_number,
                'speed' => $gps->lastLocation->speed,
                'brand' => "{$gps->vehicle->brand_name} {$gps->vehicle->model_name}",
                'business_unit' => $gps->vehicle->business_unit_name ?? null,
                'fuel_type' => $gps->engineDetail->fuelType->description ?? null,
                'status' => $statuses->where('code', $gps->vehicle->status)->first()->name ?? null,
            ];
        });
    }
}
