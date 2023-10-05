<?php

namespace App\Http\Requests\VehicleManagement;

use App\Traits\VehicleRegistrationNumberRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StatusChangeRequest extends FormRequest
{
    use VehicleRegistrationNumberRules;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can(config('rights.vehicle_status_change'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'vehicleRegistration' => $this->vehicleRegistrationNumber(),
            'status' => 'string|max:2',
            'remarks' => 'string|max:255',
        ];
    }
}
