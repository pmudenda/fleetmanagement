<?php

namespace App\Http\Requests\VehicleManagement;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OdometerLogs extends FormRequest
{
    const REQUIRED_STRING_MAX_10 = 'required|string|max:10';
    const REQUIRED_NUMERIC = 'required|numeric';
    const REQUIRED_DATE = 'required|date_format:Y-m-d';
    const EXCLUDE_UNLESS_MACHINERY_TYPE_MV_REQUIRED_NUMERIC = 'exclude_unless:machineryType,MV|required|numeric';

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
            "periodFrom" => self::REQUIRED_DATE,
            "periodTo" => self::REQUIRED_DATE,
            "vehOpeningReading" => self::EXCLUDE_UNLESS_MACHINERY_TYPE_MV_REQUIRED_NUMERIC,
            "vehClosingReading" => self::EXCLUDE_UNLESS_MACHINERY_TYPE_MV_REQUIRED_NUMERIC,
            "vehDifference" => self::EXCLUDE_UNLESS_MACHINERY_TYPE_MV_REQUIRED_NUMERIC,
            "openingReading" => 'nullable|numeric',
            "closingReading" => 'nullable|numeric',
            "difference" => 'exclude_unless:machineryType,|nullable|numeric',
            "fuelIssued" => self::REQUIRED_NUMERIC,
            "startOdometer" => self::REQUIRED_NUMERIC,
            "endOdometer" => self::REQUIRED_NUMERIC,

            "items.*.dateFrom" => self::REQUIRED_DATE,
            "items.*.dateTo" => self::REQUIRED_DATE,
            "items.*.fuelIssued" => self::REQUIRED_NUMERIC,
            "items.*.startOdometer" => self::REQUIRED_NUMERIC,
            "items.*.endOdometer" => self::REQUIRED_NUMERIC,
            "items.*.difference" => self::REQUIRED_NUMERIC,
            "items.*.authorisedBy" => self::REQUIRED_STRING_MAX_10,
            "items.*.authorizationDate" => self::REQUIRED_DATE,
            "items.*.driver" => self::REQUIRED_STRING_MAX_10,
        ];
    }
}
