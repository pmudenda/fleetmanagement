<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserOnboardingRequest extends FormRequest
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
            'bu_code' => 'required',
            'cc_code' => 'required',
            'password' => 'required',
            'staff_supervisor' => 'required',
            'user_profile' => 'required',
            'business_area' => 'required|string|max:2'
        ];
    }
}
