<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class WorkOrderClosure extends FormRequest
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
            'exitDate' => 'required',
            'timeOut' => 'required',
            'exitOdometer' => 'required',
            'exitFuelLevel' => 'required',
            'driver_out' => 'required|string|max:10',
            'driver_name_out' => 'required',
            'closureRemarks' => 'required:string|max:255',

            'items.*.workshopSection' => 'required',
            'items.*.dateOfWork' => 'required',
            'items.*.mechanic' => 'required',
            'items.*.hoursWorked' => 'required',
            'items.*.ratePerHour' => 'required',
            'items.*.totalAmount' => 'required',
            'items.*.defect' => 'required',
            'items.*.shiftType' => 'required',
        ];
    }
}
