<?php

namespace App\Livewire\VehicleManagement\Insurance;

use App\Models\VehicleManagement\Insurance;
use App\Models\VehicleManagement\InsuranceCompany;
use App\Models\VehicleManagement\VehicleHeader;
use Livewire\Component;
use Livewire\WithPagination;

class InsuranceCreate extends Component {
    use WithPagination;

    public $vehicle, $reg_no;
    public Insurance $insurance;

    public function mount() {
        $this->insurance = new Insurance();
    }

    protected function rules() {
        return [
            'reg_no' => 'required|exists:vm_vehicle_header,registration_number',
            'insurance.policy_number' => 'required',
            'insurance.certificate_number' => 'required',
            'insurance.period_from' => 'required|date',
            'insurance.period_to' => 'required|date',
            'insurance.insured_amount' => 'required|numeric',
            'insurance.premium' => 'required|numeric',
            'insurance.payment_date' => 'required|numeric',
            'insurance.type' => 'required',
        ];
    }

    public function render() {
        $companies = InsuranceCompany::all();
        return view('livewire.vehicle-management.insurance.insurance-create',compact('companies'));
    }

    public function search() {
        $this->validateOnly('reg_no');
        $this->vehicle = VehicleHeader::with('statusInfo')->where('registration_number', $this->reg_no)->first();
    }
}
