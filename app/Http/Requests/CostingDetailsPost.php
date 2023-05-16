<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CostingDetailsPost extends FormRequest
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
            'assetNumber' => 'required|string|max:100',
            'bookValue' => 'required|numeric',
            'costOfLicense' => 'required|numeric',
            'costPrice' => 'required|numeric',
            'premium' => 'required|numeric',
            'yearOfPurchase' => 'required|numeric',
            'supplierName' => 'required|string',
            'purchaseOrderDocument' => 'nullable|file|mimes:jpg,jpeg,png,bmp,tif,tiff,pdf',
        ];
    }
}
