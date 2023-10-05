<?php

namespace App\Http\Requests;

use App\Traits\VehicleRegistrationNumberRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AccidentRecordingRequest extends FormRequest
{
    use VehicleRegistrationNumberRules;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'registrationNo' => $this->vehicleRegistrationNumber(),
            'insured' => 'required',
            'mileage' => 'required',
            'accidentType' => 'required',
            'accidentNature' => 'required',
            'peopleInvolved' => 'nullable',
            'other_people_involved' => 'required',
            'day_of_week' => 'required',
            'death' => 'required',
            'other_vehicle_involved' => 'required',
            'location' => 'required',
            'area' => 'required',
            'property' => 'required',
            'date' => 'required',
            'time' => 'required',
            'guilty' => 'required',
            'driver_staff_number' => 'required',
            'driver_name' => 'required',
            'experience' => 'required',
            'num_passengers' => 'required'
        ];
    }
}
