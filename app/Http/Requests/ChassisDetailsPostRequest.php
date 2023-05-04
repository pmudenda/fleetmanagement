<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ChassisDetailsPostRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'doctype' => '',
            'headerId' => '',
            'chassisNumber' => 'required',
            'chassisNumber' => 'exclude_unless:chassisDetailsId,0|unique:App\Models\vehiclemanagement\ChassisDetail,chassis_number',
            'engineNumber' => 'required',
            'engineNumber' => 'exclude_unless:chassisDetailsId,0|unique:App\Models\vehiclemanagement\ChassisDetail,engine_number',
            'whiteBookSerial' => 'required',
            'yearOfManufacture' => 'required|numeric',
            'chargeOutRate' => 'required|decimal:2',
            'requiredMinimumDrivingLicense' => 'required',
            'initialOdometerReading' => 'required|numeric',
            'currentOdometerReading' => 'required|numeric',
            'odometerReadingLastService' => 'required|numeric',
            'nextServiceOdometerReading' => 'required|numeric',
            'inspectionDate' => 'required|date_format:Y-m-d',
            'registrationDate' => 'required|date_format:Y-m-d',

            'motor_vehicle_certificate' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff,pdf',
            'insurance_cover_note' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff,pdf',
            'front_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
            'rear_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
            'right_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
            'left_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
        ];
    }
}
