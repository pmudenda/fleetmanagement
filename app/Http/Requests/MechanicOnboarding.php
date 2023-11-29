<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MechanicOnboarding extends FormRequest
{
    const REQUIRED_STRING = 'required|string';

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
            'business_unit_code' => 'required',
            'cost_center_code' => 'required',
            'name' => 'required',
            'staff_number' => 'required|string|max:10',
            'grade' => 'required|string|max:3',
            'job_title' => self::REQUIRED_STRING,
            'staff_email' => self::REQUIRED_STRING,
            'mobile_no' => self::REQUIRED_STRING,
            'directorate' => self::REQUIRED_STRING,
            'user_unit' => self::REQUIRED_STRING,
            'nrc' => self::REQUIRED_STRING,
            'workshopSupervisor' => self::REQUIRED_STRING,
            'workshopCode' => self::REQUIRED_STRING,
            'workShopSection' => self::REQUIRED_STRING,
            'business_area' => 'required|string|max:2'
        ];
    }
}
