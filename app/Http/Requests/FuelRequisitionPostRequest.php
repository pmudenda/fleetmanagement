<?php

namespace App\Http\Requests;

use App\Enums\RequisitionTypes;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FuelRequisitionPostRequest extends FormRequest
{
    const REQUIRED_NUMERIC = 'required|numeric';
    const EXCLUDE_UNLESS_REQUISITION_TYPE = 'exclude_unless:requisition_type,';

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
            'odometer_reading' => self::REQUIRED_NUMERIC,
            'fuel_allocation' => self::REQUIRED_NUMERIC,
            'request_date' => 'required|date_format:d/m/Y',
            'next_fuel_date' => 'required|date_format:d/m/Y',
            'justification' => 'required|max:255',
            'material_description' => 'required|max:255',
            'unit_of_measure' => 'required',
            'material_quantity' => self::REQUIRED_NUMERIC,
            'material_price' => self::REQUIRED_NUMERIC,
            'material_amount' => self::REQUIRED_NUMERIC,
            'cost_center_name' => 'exclude_unless:CostAssignedTo,CostCenterBasedRequisition|required|string',
            'cost_centre_code' => 'exclude_unless:CostAssignedTo,CostCenterBasedRequisition|required|string',
            'project_code' => 'exclude_unless:CostAssignedTo,ProjectBasedRequisition|required|string',
            'ProjectName' => 'exclude_unless:CostAssignedTo,ProjectBasedRequisition|required|string',
            // out of town specific validation
            'departure_date' => self::EXCLUDE_UNLESS_REQUISITION_TYPE
                . RequisitionTypes::OutOfTown->value . '|required|date_format:Y-m-d',
            'return_date' => self::EXCLUDE_UNLESS_REQUISITION_TYPE
                . RequisitionTypes::OutOfTown->value . '|required|date_format:Y-m-d',
            'authorityToTravel' => self::EXCLUDE_UNLESS_REQUISITION_TYPE
                . RequisitionTypes::OutOfTown->value . '|file|mimes:jpg,jpeg,png,pdf',
            'destinationTown' => self::EXCLUDE_UNLESS_REQUISITION_TYPE
                . RequisitionTypes::OutOfTown->value . '|required|string',
            'departureTown' => self::EXCLUDE_UNLESS_REQUISITION_TYPE
                . RequisitionTypes::OutOfTown->value . '|required|string',
        ];
    }
}
