<?php

namespace App\Http\Requests;

use App\Models\vehiclemanagement\VehicleHeader;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VehicleHeaderRequest extends FormRequest
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
            'brand' => 'required|string',
            'user_unit' => 'required',
            'model' => 'required',
            'bodyType' => 'required',
            'registrationNumber' => 'required',
            'registrationNumber' => 'exclude_unless:headerId,0|unique:App\Models\vehiclemanagement\VehicleHeader,registration_number',
            'registration_type' => 'required'
        ];
    }
}
