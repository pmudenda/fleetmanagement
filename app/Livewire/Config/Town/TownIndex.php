<?php

namespace App\Livewire\Config\Town;

use App\Models\Town;
use Livewire\Component;
use Livewire\WithPagination;

class TownIndex extends Component
{
    use WithPagination;
    public function render()
    {
        $towns = Town::paginate(10);
        return view('livewire.config.town.town-index',compact('towns'));
    }

    public function remove(Town $town){
        $town->delete();
        $this->dispatch('message', 'Workshop Removed successfully');

    }
}
