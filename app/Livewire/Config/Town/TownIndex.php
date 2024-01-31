<?php

namespace App\Livewire\Config\Town;

use App\Models\Town;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class TownIndex extends Component
{
    use WithPagination;
    public $search;
    public function render()
    {
        $towns = Town::when($this->search, function (Builder $query) {
            $search = Str::upper($this->search);
            $query->where('town_name', 'like', "%{$search}%");
        })->paginate(10);
        return view('livewire.config.town.town-index',compact('towns'));
    }

    public function remove(Town $town){
        $town->delete();
        $this->dispatch('message', 'Workshop Removed successfully');

    }
}
