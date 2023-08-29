<?php

namespace App\Http\Requests\VehicleManagement;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ChassisDetailsPostRequest extends FormRequest
{
    const REQUIRED_NUMERIC = 'required|numeric';
    const REQUIRED_FILE_MIMES = 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff';
    const EXCLUDE_UNLESS_CHASSIS_DETAILS = 'exclude_unless:chassisDetailsId,
    0|required|unique:App\Models\VehicleManagement\ChassisDetail';

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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'doctype' => '',
            'headerId' => '',
            'chassisNumber' => self::EXCLUDE_UNLESS_CHASSIS_DETAILS . ',chassis_number',
            'engineNumber' => self::EXCLUDE_UNLESS_CHASSIS_DETAILS . ',engine_number',
            'whiteBookSerial' => self::EXCLUDE_UNLESS_CHASSIS_DETAILS . ',white_book_serial',
            'yearOfManufacture' => self::REQUIRED_NUMERIC,
            'chargeOutRate' => self::REQUIRED_NUMERIC,
            'requiredMinimumDrivingLicense' => 'required',
            'initialOdometerReading' => self::REQUIRED_NUMERIC,
            'registrationDate' => 'required|date_format:Y-m-d',

            'motor_vehicle_certificate' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff,pdf',
            'insurance_cover_note' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff,pdf',
            'front_view' => self::REQUIRED_FILE_MIMES,
            'rear_view' => self::REQUIRED_FILE_MIMES,
            'right_view' => self::REQUIRED_FILE_MIMES,
            'left_view' => self::REQUIRED_FILE_MIMES
        ];
    }
}
