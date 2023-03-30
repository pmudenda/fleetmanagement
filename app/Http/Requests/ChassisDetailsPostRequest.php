<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            'chassisNumber' =>  'required',
            'engineNumber' =>  'required',
            'whiteBookSerial' =>  'required',
            'yearOfManufacture' =>  'required',
            'registrationDate' =>  'required',
            'chargeOutRate' =>  'required',
            'requiredMinimumDrivingLicense' =>  'required',
            'initialOdometerReading' =>  'required',
            'currentOdometerReading' =>  'required',
            'odometerReadingLastService' =>  'required',
            'nextServiceOdometerReading' =>  'required',
            'inspectionDate' =>  'required',

            'motor_vehicle_certificate' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff,pdf',
            'insurance_cover_note' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff,pdf',
            'front_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
            'rear_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
            'right_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
            'left_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
        ];
    }
}
