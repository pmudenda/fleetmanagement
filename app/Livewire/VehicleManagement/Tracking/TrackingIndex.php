<?php

namespace App\Livewire\VehicleManagement\Tracking;

use App\Events\Tracking\CurrentLocationEvent;
use App\Helpers\StatusHelper;
use App\Models\Settings\general\Status;
use App\Models\VehicleManagement\Tracking\Gps;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class TrackingIndex extends Component {
    use WithPagination;

    public $gpses = [];
    public $lastloactions = [];

    #[Validate('string')]
    public $search;

    public function mount(){
        $devices = Gps::with('vehicle.roadTax')->orderBy('connected_at', 'asc')
            ->get();

        foreach ($devices as $device) {
            $raw = Redis::client()->get("last-location-{$device->imei}");

            if ($raw) {
                $this->lastloactions[] = $raw ? json_decode($raw, true) : [];
            }
        }

    }

    public function render() {

        return view('livewire.vehicle-management.tracking.tracking-index');
    }


}
