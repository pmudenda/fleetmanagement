<?php

namespace App\Http\Requests;

use App\Enums\RepairTypes;
use App\Enums\RequisitionTypes;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class JobCardRequest extends FormRequest
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
            'vehicle_registration' => 'required:string|max:10',
            'date_of_req' => 'required|date_format:Y-m-d',
            'workshop' => 'required:string',
            'timeIn'=>'required:date_format',
            'repairType' => 'required:string',
            'service_advisor' => 'required:string',
            'accident_number' => 'exclude_unless:repairType,'.RepairTypes::AccidentRepair->value.'|required|string',
            'current_odometer' => 'required:numeric',
            'fuel_level' => 'required:string',
            'sub_fuel_level' => 'nullable:string',
            'driver_staff_number' => 'required:string',
        ];
    }
}
