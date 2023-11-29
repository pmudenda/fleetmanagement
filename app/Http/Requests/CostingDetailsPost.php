<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CostingDetailsPost extends FormRequest
{
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
            'assetNumber' => 'required|string|max:100',
            'bookValue' => self::REQUIRED_NUMERIC,
            'costOfLicense' => self::REQUIRED_NUMERIC,
            'costPrice' => self::REQUIRED_NUMERIC,
            'premium' => self::REQUIRED_NUMERIC,
            'yearOfPurchase' => self::REQUIRED_NUMERIC,
            'supplierName' => 'required|string',
            'purchaseOrderDocument' => 'nullable|file|mimes:jpg,jpeg,png,bmp,tif,tiff,pdf|max:3072',
        ];
    }
}
