<?php

namespace App\Http\Requests\WorkShopManagement;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class JobCardTaskAssignment extends FormRequest
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
            'items.*.workshopSection' => 'required',
            'items.*.mechanic' => 'required',
            'items.*.defect' => 'required',

            'items.*.dateOfWork' => 'nullable',
            'items.*.shiftType' => 'nullable',
            'items.*.hoursWorked' => 'nullable',
            'items.*.ratePerHour' => 'nullable',
            'items.*.totalAmount' => 'nullable',
        ];
    }
}
