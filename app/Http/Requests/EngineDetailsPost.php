<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EngineDetailsPost extends FormRequest
{
    const REQUIRED_NUMERIC = 'required|numeric';
    const REQUIRED_STRING = 'required|string';
    const REQUIRED_STRING_MAX_100 = 'required|string|max:100';

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
            'numberOfCylinders' => self::REQUIRED_NUMERIC,
            'engineCapacity' => self::REQUIRED_NUMERIC,
            'fuelTypes' => self::REQUIRED_STRING,
            'fuelConsumption' => self::REQUIRED_NUMERIC,
            'engineType' => self::REQUIRED_STRING_MAX_100,
            'claimedEnginePower' => self::REQUIRED_NUMERIC,
            'actualEnginePower' => self::REQUIRED_NUMERIC,
            'engineBrand' => self::REQUIRED_STRING,
            'transmission_type' => self::REQUIRED_STRING,
            'tank_capacity' => self::REQUIRED_NUMERIC,
            'numberOfTyres' => self::REQUIRED_NUMERIC,
            'tyreBrand' => self::REQUIRED_STRING_MAX_100,
            'frontTyreSize' => self::REQUIRED_STRING,
            'rearTyreSize' => self::REQUIRED_STRING,
            'batteryBrand' => self::REQUIRED_STRING_MAX_100,
            'batterySize' => self::REQUIRED_STRING,
            'batteryPower' => self::REQUIRED_NUMERIC,
        ];
    }
}
