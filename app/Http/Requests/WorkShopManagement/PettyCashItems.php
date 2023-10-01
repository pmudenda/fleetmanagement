<?php

namespace App\Http\Requests\WorkShopManagement;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PettyCashItems extends FormRequest
{
    const REQUIRED_STRING_RULE = 'required|string';
    const REQUIRED_NUMERIC_RULE = 'required:numeric';

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
            "workshopReference" => self::REQUIRED_STRING_RULE . '|max:20',
            "jobCardNumber" => self::REQUIRED_STRING_RULE . '|max:20',
            "costCenter" => self::REQUIRED_STRING_RULE,
            "pettyCashBuyItemType" => self::REQUIRED_STRING_RULE,
            "imprestWorkshopCode" => self::REQUIRED_STRING_RULE,
            "registrationNumber" => self::REQUIRED_STRING_RULE . '|max:10',
            "imprestPurchaseOffice" => self::REQUIRED_STRING_RULE,
            'totalPayment' => 'required|numeric|max:2000',
            "items.*.imprestVehicleRegistration" => self::REQUIRED_STRING_RULE . '|max:10',
            "items.*.imprestArticles" => self::REQUIRED_STRING_RULE,
            "items.*.imprestArticleCode" => self::REQUIRED_STRING_RULE,
            "items.*.imprestArticleDescription" => self::REQUIRED_STRING_RULE . '|max:255',
            "items.*.imprestItemQty" => self::REQUIRED_NUMERIC_RULE,
            "items.*.imprestItemUnitOfMeasure" => self::REQUIRED_STRING_RULE,
            "items.*.imprestItemUnitPrice" => self::REQUIRED_NUMERIC_RULE,
            "items.*.imprestItemTotalPrice" => self::REQUIRED_NUMERIC_RULE,
        ];
    }
}
