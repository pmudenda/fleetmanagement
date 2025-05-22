<?php

namespace App\Livewire\GatePass;

use App\Enums\GatePassStatus;
use App\Enums\GatePassType;
use App\Helpers\StatusHelper;
use App\Models\DistanceChart;
use App\Models\GatePass\GatePass;
use App\Models\Town;
use App\Models\VehicleManagement\VehicleHeader;
use App\Rules\GatePassRule;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component {
    use WithFileUploads;

    public $type;
    public $reg_no;
    public $expires_at;
    public $purpose;
    public $departure_at;
    public $departure_town;
    public $destination_town;
    public $attachment;

    protected function rules() {
        return [
            'type' => 'required',
            'reg_no' => ['required', new GatePassRule],
            'expires_at' => 'required|date|after:today',
            'purpose' => 'required',
            'departure_at' => [Rule::requiredIf($this->type == GatePassType::AUTHORITY_TO_TRAVEL)],
            'attachment' => [Rule::requiredIf($this->type == GatePassType::STAND_BY)],
            'departure_town' => [Rule::requiredIf($this->type == GatePassType::AUTHORITY_TO_TRAVEL)],
            'destination_town' => [Rule::requiredIf($this->type == GatePassType::AUTHORITY_TO_TRAVEL)],
        ];
    }

    public function render() {
        $types = GatePassType::cases();
        $towns = Town::orderBy('town_name')->get();
        $dtowns = DistanceChart::orderBy('town_to', 'asc')->where('town_from', $this->departure_town)->get();

        $minDate = now()->toDateString();
        $maxDate = '';

        if ($this->type == GatePassType::STAND_BY->value) {
            $maxDate = now()->addDays(7)->toDateString();
        } else if ($this->type == GatePassType::GENERAL->value) {
            $maxDate = now()->endOfDay()->toDateString();
            $this->expires_at = now()->endOfDay()->toDateString();
        }

        return view('livewire.gate-pass.create', compact('types', 'towns', 'dtowns', 'minDate', 'maxDate'));
    }

    public function save() {
        $this->validate();
        $gatePass = GatePass::create([
            'status' => GatePassStatus::NEW,
            'user_id' => auth()->user()->id,
            ...$this->all()
        ]);
        $gatePass->refresh();

        if ($this->attachment) {
            $gatePass->addMedia($this->attachment)->toMediaCollection('attachments');
        }
        $this->dispatch('message', 'Gate Pass created Successfully');
        $this->redirect(route('gate-pass.show', $gatePass));;

    }

    public function updatedType() {
        $this->reset([
            'departure_town',
            'destination_town',
            'departure_at',
            'attachment'
        ]);
    }
}
