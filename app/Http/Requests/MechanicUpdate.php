<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MechanicUpdate extends FormRequest
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
            'mechanicId' => 'required|numeric|exists:App\Models\WorkShopManagement\Mechanic,id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|numeric',
            'workshop_code' => 'required|string|max:4',
            'workShopSection' => 'required|string',
            'workshopSupervisor' => 'required|string'
        ];
    }
}
