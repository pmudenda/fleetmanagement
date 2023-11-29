<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FuelRequisitionUpdate extends FormRequest
{
    const NULLABLE_STRING = 'nullable|string';

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
            'material_amount' => 'nullable|numeric',
            'departureTown' => self::NULLABLE_STRING,
            'destinationTown' => self::NULLABLE_STRING,
            'justification' => self::NULLABLE_STRING,
            'cost_centre_code' => self::NULLABLE_STRING,
            'CostAssignedTo' => self::NULLABLE_STRING,
            'departure_date' => self::NULLABLE_STRING,
            'return_date' => self::NULLABLE_STRING,
            'odometer_reading' => self::NULLABLE_STRING
        ];
    }
}
