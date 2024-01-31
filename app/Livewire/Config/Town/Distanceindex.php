<?php

namespace App\Livewire\Config\Town;

use App\Models\DistanceChart;
use App\Models\Town;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Distanceindex extends Component
{
    use WithPagination;

    public Town $town;
    public $search;

    public function mount(Town $town){
        $this->town = $town;
    }

    public function render()
    {
        $distances = $this->town->distances()->when($this->search, function (Builder $query) {
            $search = Str::upper($this->search);
            $query->where('town_to', 'like', "%{$search}%");
        })->paginate(10);
        return view('livewire.config.town.distanceindex',compact('distances'));
    }

    public function remove(DistanceChart $distanceChart){
        $distanceChart->delete();
        $this->dispatch('message', 'Town Distance Removed successfully');

    }
}
