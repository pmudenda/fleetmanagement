<?php

namespace App\Http\Requests\VehicleManagement;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TomCardAssignment extends FormRequest
{
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
            'vehicleRegistration' =>
                'required|string|unique:App\Models\VehicleManagement\VehicleHeader,registration_number',
            'cardNumber' => 'required|string|unique:App\Models\VehicleManagement\TomCardAllocation,card_number',
            'expiryDate' => 'required',
            'comments' => 'required',
        ];
    }
}
