<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class WorkshopRequisitionRequest extends FormRequest
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
            'item.*.articles' => 'required|string',
            'item.*.quantity' => 'required|numeric',
            'item.*.registration' => 'required|string',
            'item.*.technical_specification' => 'required|string',
            'item.*.total_price' => 'required|numeric',
            'item.*.unit_of_measure' => 'required|string',
            'item.*.unit_price' => 'required|numeric',

            'job_card_no' => 'required|string',
            'workshop_reference' => 'required|string',
            'modelName' => 'required|string',
            'purchase_office' => 'required|string',
            'remarks' => 'required|string',
            'request_date' => 'required|string',
            'store_code' => 'required|string',
            'store_name' => 'required|string',
            'supplier' => 'nullable|string',
            'workshop_code' => 'required|string',
        ];
    }
}
