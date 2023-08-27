<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OdometerLogs extends FormRequest
{
    const REQUIRED_STRING_MAX_10 = 'required|string|max:10';
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
            "vehicleRegistration" => self::REQUIRED_STRING_MAX_10,
            "machineryType" => 'required|string',
            "periodFrom" => 'required|date_format:Y-m-d',
            "periodTo" => 'required|date_format:Y-m-d',
            "vehOpeningReading" => 'exclude_unless:machineryType,MV|required|numeric',
            "vehClosingReading" => 'exclude_unless:machineryType,MV|required|numeric',
            "vehDifference" => 'exclude_unless:machineryType,MV|required|numeric',
            "openingReading" => 'nullable|numeric',
            "closingReading" => 'nullable|numeric',
            "difference" => 'exclude_unless:machineryType,|nullable|numeric',
            "fuelIssued" => self::REQUIRED_NUMERIC,
            "startOdometer" => self::REQUIRED_NUMERIC,
            "endOdometer" => self::REQUIRED_NUMERIC,

            "items.*.dateFrom" => 'required|date_format:Y-m-d',
            "items.*.dateTo" => 'required|date_format:Y-m-d',
            "items.*.fuelIssued" => self::REQUIRED_NUMERIC,
            "items.*.startOdometer" => self::REQUIRED_NUMERIC,
            "items.*.endOdometer" => self::REQUIRED_NUMERIC,
            "items.*.difference" => self::REQUIRED_NUMERIC,
            "items.*.authorisedBy" => self::REQUIRED_STRING_MAX_10,
            "items.*.authorizationDate" => 'required|date_format:Y-m-d',
            "items.*.driver" => self::REQUIRED_STRING_MAX_10,
        ];
    }
}
