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

    #[Validate('string')]
    public $search;

    public function render() {

        return view('livewire.vehicle-management.tracking.tracking-index');
    }


}
