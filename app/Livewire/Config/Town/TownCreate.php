<?php

namespace App\Livewire\Config\Town;

use App\Models\Town;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TownCreate extends Component {
    public  $town;

    protected function rules() {
        return [
            'town.town_name' => ['required', Rule::unique(Town::class, 'town_name')->ignore($this->town->id)]
        ];
    }

    public function mount() {
        $this->town = new Town;
    }

    public function render() {
        return view('livewire.config.town.town-create');
    }

    public function save() {
        $this->validate();
        $this->town->save();
        $this->dispatch('message', 'Workshop added successfully');
        $this->town = new Town();
    }
}
