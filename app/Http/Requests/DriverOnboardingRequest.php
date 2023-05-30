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
            'employee_number' => 'required|string|max:255|unique:App\Models\Driver,staff_number',
            'driver_name' => 'required|string|max:255',
            'grade' => 'required|string|max:3',
            'job_title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'license_number' => 'required|string|max:255|unique:App\Models\Driver,license_number',

            'license_date_issued' => 'required|lt:license_date_expiry|date_format:Y-m-d',
            'license_date_expiry' => 'required|gt:license_date_issued|date_format:Y-m-d',
            'license_class' => 'required|string|max:255',
            'license_front_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
            'license_back_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
            'isDesignatedDriver' => 'required|string',
            'permit_number' => 'required|string|unique:App\Models\Driver,permit_number',
            'permit_date_issued' => 'required|lt:permit_date_expiry|date_format:Y-m-d',
            'permit_date_expiry' => 'required|gt:permit_date_issued|date_format:Y-m-d',
            'permit_copy' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff,pdf',
        ];
    }
}
