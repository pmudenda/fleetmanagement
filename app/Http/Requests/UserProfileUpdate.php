<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserProfileUpdate extends FormRequest
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
            'name' => 'required',
            'userId' => 'required',
            'email' => 'required',
            'phone' => 'nullable|string',
            'area' => 'required',
            'staff_supervisor' => 'required',
            'staff_supervisorId' => 'required',
            'user_profile' => 'required',
        ];
    }
}
