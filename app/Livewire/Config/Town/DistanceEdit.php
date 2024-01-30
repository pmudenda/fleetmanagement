<?php

namespace App\Livewire\Config\Town;

use App\Models\DistanceChart;
use App\Models\Town;
use Illuminate\Validation\Rule;
use Livewire\Component;

class DistanceEdit extends Component {
    public $town;
    public DistanceChart $distance;

    protected function rules() {
        return [
            'distance.town_to' => ['required'],
            'distance.distance' => ['required', 'numeric']
        ];
    }

    public function mount(Town $town, DistanceChart $distance) {
        $this->town = $town;
        $this->distance = $distance;
    }

    public function render() {
//        dd($this->town->distances()->get()->pluck('town_to')->values());
        $towns = Town::whereNotIn('town_name', $this->town->distances()->where('town_to', '!=', $this->distance->town_to)->get()->pluck('town_to')->values())->get();
        return view('livewire.config.town.distance-edit', compact('towns'));
    }

    public function save() {
        $this->validate();
        $this->distance->town_from = $this->town->town_name;
        $this->distance->save();
        $this->dispatch('message', 'new Town Distance added successfully');
        $this->distance->distance = null;
        $this->distance->town_to = null;
        $this->render();
    }
}
