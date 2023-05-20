<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EngineDetailsPost extends FormRequest
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
            'numberOfCylinders' => 'required|numeric',
            'engineCapacity' => 'required|numeric',
            'fuelTypes' => 'required|string',
            'fuelConsumption' => 'required|numeric',
            'engineType' => 'required|string|max:100',
            'claimedEnginePower' => 'required|numeric',
            'actualEnginePower' => 'required|numeric',
            'engineBrand' => 'required|string',
            'transmission_type' => 'required|string',
            'tank_capacity' => 'required|numeric',
            'numberOfTyres' => 'required|numeric',
            'tyreBrand' => 'required|string|max:100',
            'frontTyreSize' => 'required|string',
            'rearTyreSize' => 'required|string',
            'batteryBrand' => 'required|string|max:100',
            'batterySize' => 'required|string',
            'batteryPower' => 'required|numeric',
        ];
    }
}
