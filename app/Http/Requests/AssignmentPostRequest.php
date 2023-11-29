<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AssignmentPostRequest extends FormRequest
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
            'businessArea'=> 'required',
            'directorate'=> 'required',
            'costCenter'=> 'required',
            'isPoolVehicle'=> 'required',
            'isMileageExempt'=> 'required',
            'responsibleHOD' => 'exclude_unless:isPoolVehicle,Y|required|string',
            'responsibleHODId' => 'exclude_unless:isPoolVehicle,Y|required|string',
            'vehicleHolder' => 'exclude_unless:isPoolVehicle,N|required|string',
            'vehicleHolderId' => 'exclude_unless:isPoolVehicle,N|required|string'
        ];
    }
}
