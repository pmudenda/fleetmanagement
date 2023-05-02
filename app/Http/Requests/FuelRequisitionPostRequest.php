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
            'requisition_type' => 'required|string',
            'odometer_reading' => 'required|numeric',
            'fuel_allocation' => 'required|numeric',
            'request_date' => 'required|date_format:d/m/Y',
            'next_fuel_date' => 'required|date_format:d/m/Y',
            'justification' => 'required|max:255',
            'material_description' => 'required|max:255',
            'unit_of_measure' => 'required',
            'material_quantity' => 'required|numeric',
            'material_price' => 'required|numeric',
            'material_amount' => 'required|numeric',
            'cost_center_name' => 'exclude_unless:CostAssignedTo,CostCenterBasedRequisition|required|string',
            'cost_centre_code' => 'exclude_unless:CostAssignedTo,CostCenterBasedRequisition|required|string',
            'project_code' => 'exclude_unless:CostAssignedTo,ProjectBasedRequisition|required|string',
            'departure_date' => 'exclude_unless:requisition_type,011|required|date_format:Y-m-d',
            'return_date' => 'exclude_unless:requisition_type,011|required|date_format:Y-m-d',
        ];
    }
}
