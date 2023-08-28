<?php

namespace App\Http\Requests\WorkShopManagement;

use App\Enums\RepairTypes;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobCardRequest extends FormRequest
{
    const REQUIRED_STRING = 'required:string';

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
            'workshop' => self::REQUIRED_STRING,
            'timeIn' => 'required:date_format',
            'repairType' => self::REQUIRED_STRING,
            'service_advisor' => self::REQUIRED_STRING,
            'accident_number' => [
                'exclude_unless:repairType,' . RepairTypes::AccidentRepair->value . '|required|string',
                Rule::exists('vm_accident')->where(function (Builder $query) {
                    return $query->where('reference', 1);
                }),
            ],

            'current_odometer' => 'required:numeric',
            'fuel_level' => self::REQUIRED_STRING,
            'sub_fuel_level' => 'nullable:string',
            'driver_staff_number' => self::REQUIRED_STRING,
        ];
    }
}
