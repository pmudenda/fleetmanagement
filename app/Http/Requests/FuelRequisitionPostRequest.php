<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FuelRequisitionPostRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_registration' => 'required',
            'vehicle_description' => 'required',
            'CostAssignedTo' => 'required',
            'requisition_type' => 'required',
            'odometer_reading' => 'required|number',
            'fuel_allocation' => 'required|number',
            'request_date' => 'required|date_format:d/m/Y',
            'next_fuel_date' => 'required|date_format:d/m/Y',
            'justification' => 'required|max:255',
            'material_description' => 'required|max:255',
            'unit_of_measure' => 'required',
            'material_quantity' => 'required|number',
            'material_price' => 'required|decimal:2',
            'material_amount' => 'required|decimal:2'];
    }
}
