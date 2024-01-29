<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DriverUpdateRequest extends FormRequest
{
    const REQUIRED_STRING_MAX_255 = 'required|string|max:255';

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
            'driver_name' => 'required|string|max:100',
            'grade' => 'required|string|max:3',
            'job_title' => self::REQUIRED_STRING_MAX_255,
            'location' => self::REQUIRED_STRING_MAX_255,
            'department' => 'required|string|max:150',
            'license_number' => 'required|string|max:255',

            'license_date_issued' => 'required|date_format:Y-m-d|before:license_date_expiry',
            'license_date_expiry' => 'required|date_format:Y-m-d|after:license_date_issued',
            'license_class' => self::REQUIRED_STRING_MAX_255,
            'license_front_view' => 'nullable|file|mimes:jpg,jpeg,png,bmp,tif,tiff|max:1024',
            'license_back_view' => 'nullable|file|mimes:jpg,jpeg,png,bmp,tif,tiff|max:1024',
            'isDesignatedDriver' => 'required|string',
            'permit_number' => 'required|string',
            'permit_date_issued' => 'required|date_format:Y-m-d|before:permit_date_expiry',
            'permit_date_expiry' => 'required|date_format:Y-m-d|after:permit_date_issued',
            'permit_copy' => 'nullable|file|mimes:jpg,jpeg,png,bmp,tif,tiff,pdf|max:2048',
        ];
    }
}
