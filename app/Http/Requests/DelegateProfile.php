<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DelegateProfile extends FormRequest
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
            'profileOwner' => 'required|string|max:10|exists:SEC_USERS,staff_no',
            'staffNumber' => 'required|string|max:10|exists:SEC_USERS,staff_no',
            'employeeName' => 'required|string',
            'currentProfile' => 'required|numeric',
            'startDate' => 'required|date|after_or_equal:'.Carbon::now(),
            'endDate' => 'required|date|after:startDate',
            'remarks' => 'required|string|max:255|min:50',
        ];
    }
}
