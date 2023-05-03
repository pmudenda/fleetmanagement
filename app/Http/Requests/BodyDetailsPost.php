<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BodyDetailsPost extends FormRequest
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
            'height' => 'required|numeric',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'seatCapFront' => 'required|numeric',
            'tareWeight' => 'required|numeric',
            'grossWeight' => 'required|numeric',
            'distanceAxle1' => 'nullable|numeric',
            'distanceAxle2' => 'nullable|numeric',
            'distanceAxle3' => 'nullable|numeric',
            'distanceAxle4' => 'nullable|numeric',
        ];
    }
}
