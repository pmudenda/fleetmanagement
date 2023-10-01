<?php

namespace App\Http\Requests\VehicleManagement;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ETollCardRequest extends FormRequest
{
    const REQUIRED_DATE_WITH_FORMAT = 'required|date_format:d/m/Y';
    const REQUIRED = 'required';

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
            'batchNumber' => self::REQUIRED,
            'cardScheme' => self::REQUIRED,
            'cardNumber' => self::REQUIRED,
            'cardStatus' => self::REQUIRED,
            'dateIssued' => self::REQUIRED_DATE_WITH_FORMAT,
            'expiryDate' => self::REQUIRED_DATE_WITH_FORMAT,
            'cvv' => 'required|string|max:3',
            'contactNumber' => self::REQUIRED,
            'assignedTo' => self::REQUIRED,
            'responseHead' => self::REQUIRED,
            'responseHeadId' => self::REQUIRED,
            'comments' => 'nullable|string',
            'supportingDocument' => 'nullable|file|mimes:pdf|max:1024'
        ];
    }
}
