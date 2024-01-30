<?php

namespace App\Livewire\Config\Town;

use App\Models\DistanceChart;
use App\Models\Town;
use Livewire\Component;
use Livewire\WithPagination;

class Distanceindex extends Component
{
    use WithPagination;

    public Town $town;

    public function mount(Town $town){
        $this->town = $town;
    }

    public function render()
    {
        $distances = $this->town->distances()->paginate(10);
        return view('livewire.config.town.distanceindex',compact('distances'));
    }

    public function remove(DistanceChart $distanceChart){
        $distanceChart->delete();
        $distanceChart->dispatch('message', 'Town Distance Removed successfully');

    }
}
