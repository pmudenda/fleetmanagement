<?php

namespace App\Http\Requests\WorkShopManagement;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubmitJobCardToSupervisor extends FormRequest
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
            'commentsToSupervisor' => 'required|string|max:255',
            'vehicle_registration' => 'required|string|max:10',
            'job_card_number' => 'required|string|max:20',
        ];
    }
}
