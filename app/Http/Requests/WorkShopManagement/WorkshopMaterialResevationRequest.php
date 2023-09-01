<?php

namespace App\Http\Requests\WorkShopManagement;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class WorkshopMaterialResevationRequest extends FormRequest
{
    const REQUIRED_STRING = 'required|string';
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
            'items.*.articles' => self::REQUIRED_STRING,
            'items.*.quantity' => self::REQUIRED_NUMERIC,
            'items.*.registration' => self::REQUIRED_STRING,
            'items.*.technical_specification' => self::REQUIRED_STRING,
            'items.*.total_price' => self::REQUIRED_NUMERIC,
            'items.*.unit_of_measure' => self::REQUIRED_STRING,
            'items.*.unit_price' => self::REQUIRED_NUMERIC,

            'modelName' => self::REQUIRED_STRING,
            'purchase_office' => self::REQUIRED_STRING,
            'remarks' => self::REQUIRED_STRING,
            'request_date' => self::REQUIRED_STRING,
            'store_code' => self::REQUIRED_STRING,
            // 'store_name' => 'required|string',
            'supplier' => 'nullable|string',
            'workshop_code' => self::REQUIRED_STRING,
        ];
    }
}
