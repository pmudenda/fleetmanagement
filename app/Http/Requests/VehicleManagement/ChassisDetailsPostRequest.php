<?php

namespace App\Http\Requests\VehicleManagement;

use App\Models\VehicleManagement\ChassisDetail;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ChassisDetailsPostRequest extends FormRequest
{
    const REQUIRED_NUMERIC = 'required|numeric';
    const REQUIRED_FILE_MIMES = 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff';
    const EXCLUDE_UNLESS_CHASSIS_DETAILS = 'exclude_unless:chassisDetailsId,
    0|required|unique:App\Models\VehicleManagement\ChassisDetail';
    const MIME_TYPES = 'mimes:jpg,jpeg,png,bmp,tif,tiff,pdf';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    private function checkChasisDetails($column){
return [
    'exclude_unless:chassisDetailsId,0',
    'required',
    \Illuminate\Validation\Rule::unique(ChassisDetail::class,$column)->ignore($this->request->get('headerId'),'vehicle_header_id')
];
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
            'chassisNumber' => $this->checkChasisDetails('chassis_number'),
            'engineNumber' => $this->checkChasisDetails('engine_number'),
            'whiteBookSerial' => $this->checkChasisDetails('engine_number'),
            'yearOfManufacture' => self::REQUIRED_NUMERIC,
            'chargeOutRate' => self::REQUIRED_NUMERIC,
            'requiredMinimumDrivingLicense' => 'required',
            'initialOdometerReading' => self::REQUIRED_NUMERIC,
            'registrationDate' => 'required|date_format:Y-m-d',

            'motor_vehicle_certificate' => 'sometimes|required|file|' . self::MIME_TYPES . '|max:8000',
            'insurance_cover_note' => 'sometimes|required|file|'. self::MIME_TYPES . '|max:8000',
//            'front_view' => self::REQUIRED_FILE_MIMES,
//            'rear_view' => self::REQUIRED_FILE_MIMES,
//            'right_view' => self::REQUIRED_FILE_MIMES,
//            'left_view' => self::REQUIRED_FILE_MIMES
        ];
    }
}
