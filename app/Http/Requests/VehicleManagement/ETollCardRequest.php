<?php

namespace App\Http\Requests\VehicleManagement;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ETollCardRequest extends FormRequest
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
            'batchNumber' => 'required',
            'cardScheme' => 'required',
            'cardNumber' => 'required',
            'cardStatus' => 'required',
            'dateIssued' => 'required|date_format:d/m/Y',
            'expiryDate' => 'required|date_format:d/m/Y',
            'cvv' => 'required|string',
            'contactNumber' => 'required',
            'assignedTo' => 'required',
            'responseHead' => 'required',
            'responseHeadId' => 'required',
            'comments' => 'nullable|string',
            'supportingDocument' => 'nullable|file|mimes:jpg,jpeg,png,bmp,tif,tiff,pdf'
        ];
    }
}
