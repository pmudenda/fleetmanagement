<?php

namespace App\Livewire\Mechanic;

use App\Models\WorkShopManagement\Mechanic;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class WorkshopIndex extends Component {
    use WithPagination;

    public $mechanic;

    public function mount() {
        $this->mechanic = Mechanic::find($this->mechanic);
    }

    #[On('update')]
    public function render() {
        $workshops = $this->mechanic->workshops()->paginate(10);
        return view('livewire.mechanic.workshop-index', compact('workshops'));
    }

    public function remove($workshopId) {
        $this->mechanic->workshops()->updateExistingPivot($workshopId, ['deleted_at' => now()]);
        $this->dispatch('modal-close', 'close');
        $this->dispatch('message', 'Workshop removed successfully');
        $this->dispatch('update');
    }
}
