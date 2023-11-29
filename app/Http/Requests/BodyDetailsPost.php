<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BodyDetailsPost extends FormRequest
{
    const NULLABLE_NUMERIC = 'nullable|numeric';
    const REQUIRED_NUMERIC = 'required|numeric';

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
            'height' => self::REQUIRED_NUMERIC,
            'length' => self::REQUIRED_NUMERIC,
            'width' => self::REQUIRED_NUMERIC,
            'seatCapFront' => self::REQUIRED_NUMERIC,
            'tareWeight' => 'required|numeric|lt:grossWeight',
            'grossWeight' => 'required|numeric|gt:tareWeight',
            'distanceAxle1' => self::NULLABLE_NUMERIC,
            'distanceAxle2' => self::NULLABLE_NUMERIC,
            'distanceAxle3' => self::NULLABLE_NUMERIC,
            'distanceAxle4' => self::NULLABLE_NUMERIC,
        ];
    }
}
