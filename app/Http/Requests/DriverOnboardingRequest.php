<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DriverOnboardingRequest extends FormRequest
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
            'employee_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:3',
            'job_title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'license_number' => 'required|string|max:255',
            'license_date_issued' => 'required|date_format:Y-m-d',
            'license_date_expiry' => 'required|date_format:Y-m-d',
            'license_class' => 'required|string|max:255',
            'license_front_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
            'license_back_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
            'isDesignatedDriver' => 'required|string',
            'permit_number' => 'required|string',
            'permit_date_issued' => 'required|dateformat|d-m-Y',
            'permit_date_expiry' => 'required|dateformat|d-m-Y',
            'permit_copy' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff,pdf',
        ];
    }
}
