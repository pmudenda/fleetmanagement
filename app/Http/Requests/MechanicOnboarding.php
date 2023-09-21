<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MechanicOnboarding extends FormRequest
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
            'business_unit_code' => 'required',
            'cost_center_code' => 'required',
            'name' => 'required',
            'staff_number' => 'required|string|max:10',
            'grade' => 'required|string|max:3',
            'job_title' => 'required|string',
            'staff_email' => 'required|string',
            'mobile_no' => 'required|string',
            'directorate' => 'required|string',
            'user_unit' => 'required|string',
            'nrc' => 'required|string',
            'workshopSupervisor' => 'required|string',
            'workshopCode' => 'required|string',
            'workShopSection' => 'required|string',
            'business_area' => 'required|string|max:2'
        ];
    }
}
