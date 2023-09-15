<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FuelRequisitionUpdate extends FormRequest
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
            'reference' => 'required|string',
            'Comments' => 'required|string',
            'material_quantity' => 'required|numeric',
            'material_price' => 'required|numeric',
            'material_amount' => 'required|numeric',
            'departureTown' => 'nullable|string',
            'destinationTown' => 'nullable|string',
            'justification' => 'nullable|string',
            'cost_centre_code' => 'nullable|string',
            'CostAssignedTo' => 'nullable|string',
            'departure_date' => 'nullable|string',
            'return_date' => 'nullable|string',
            'odometer_reading' => 'nullable|string'
        ];
    }
}
