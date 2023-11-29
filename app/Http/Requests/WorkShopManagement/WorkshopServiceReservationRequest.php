<?php

namespace App\Http\Requests\WorkShopManagement;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class WorkshopServiceReservationRequest extends FormRequest
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
            'item.*.service_article' => self::REQUIRED_STRING,
            'item.*.service_quantity' => self::REQUIRED_NUMERIC,
            'item.*.vehicle_registration' => self::REQUIRED_STRING,
            'item.*.service_technical_specification' => self::REQUIRED_STRING,
            'item.*.service_total_price' => self::REQUIRED_NUMERIC,
            'item.*.service_unit_of_measure' => self::REQUIRED_STRING,
            'item.*.service_unit_price' => self::REQUIRED_NUMERIC,
            'modelName' => self::REQUIRED_STRING,
            'purchase_office' => self::REQUIRED_STRING,
            'remarks' => self::REQUIRED_STRING,
            'request_date' => self::REQUIRED_STRING,
            'supplier' => 'nullable|string',
            'workshop_code' => self::REQUIRED_STRING,
        ];
    }
}
