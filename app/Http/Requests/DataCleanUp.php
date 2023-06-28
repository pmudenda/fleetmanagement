<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DataCleanUp extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "registrationNumber" => 'required|string',
            "vehicleMake" => 'required|string',
            "model" => 'required|string',
            "model_code" => 'required|string',
            "engineNo" => 'required|string',
            "chassisNo" => 'required|string',
            "isBranded" => 'required|string',
            "transmission" => 'required|string',
            "odometer" => 'required|string',
            "directorate" => 'required|string',
            "organizationalUnit" => 'required|string',
            "responsibleHOD" => 'exclude_unless:isPoolVehicle,YES|required|string',
            "responsibleHODId" => 'exclude_unless:isPoolVehicle,YES|required|string',
            "supervisor" => 'exclude_unless:isPoolVehicle,YES|required|string',
            "supervisorId" => 'exclude_unless:isPoolVehicle,YES|required|string',
            "operator" => 'exclude_unless:isPoolVehicle,YES|required|string',
            "operatorId" => 'exclude_unless:isPoolVehicle,YES|required|string',
            "assignedTo" => 'exclude_unless:isPoolVehicle,NO|required|string',
            "assignedToId" => 'exclude_unless:isPoolVehicle,NO|required|string',
            "isPoolVehicle" => 'required|string',

            'front_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
            'rear_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
            'right_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff',
            'left_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff'
        ];
    }
}
