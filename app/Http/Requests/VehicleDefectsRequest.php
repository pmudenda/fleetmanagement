<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VehicleDefectsRequest extends FormRequest
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
            'item.*.date_def' => 'required',
            'item.*.defect' => 'required',
            'item.*.defectCategory' => 'required',
            'item.*.vehicleSystem' => 'required',
            'item.*.workshopSection' => 'required',
            'workshop_reference' => 'required|string|max:20',
            'modelName' => 'required',
            'remarks' => 'nullable|string|max:255'
        ];
    }
}
