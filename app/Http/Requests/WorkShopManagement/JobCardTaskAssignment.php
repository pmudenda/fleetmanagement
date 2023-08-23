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
            'workshopReference' =>  'required|',
            'jobCardNumber' =>  'required|',
            'items.*.workshopSection' => 'required',
            'items.*.mechanic' => 'required',
            'items.*.assignedDefect' => 'required',
            'items.*.assignedDefectId' => 'required',
            'items.*.jobCardInstruction' => 'required|string|max:500',

            'items.*.dateOfWork' => 'nullable',
            'items.*.shiftType' => 'nullable',
            'items.*.hoursWorked' => 'nullable',
            'items.*.ratePerHour' => 'nullable',
            'items.*.totalAmount' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'items.*.id.jobCardInstruction' => 'Job Card Instruction Is Missing',
        ];
    }
}
