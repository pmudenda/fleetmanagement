<?php

namespace App\Services\VehicleManagement\OnBoarding;

use App\Enums\VehicleStatusEnum;
use App\Exceptions\VehicleOnBoardingException;
use App\Http\Requests\ChassisDetailsPostRequest;
use App\Models\vehiclemanagement\ChassisDetail;
use App\Services\FileUploads\FileUploadService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OnBoardingService
{
    private FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * @param ChassisDetailsPostRequest $request
     * @return ChassisDetail
     * @throws VehicleOnBoardingException
     */
    public function processChassisDetails(ChassisDetailsPostRequest $request): ChassisDetail
    {

        $user = auth()->user();

        $chassisNumber = $request->input('chassisNumber');

        $exitingRegistration = ChassisDetail::where('chassis_number', $chassisNumber)->first();

        if (!empty($exitingRegistration)) {
            throw new VehicleOnBoardingException('The Chassis Number you have provided has already been registered');
        }

        DB::beginTransaction();

        $model = ChassisDetail::create([
            'vehicle_header_id' => $request->input('headerId'),
            'chassis_number' => $chassisNumber,
            'date_on_road' => Carbon::parse($request->input('registrationDate')),
            'engine_number' => $request->input('engineNumber'),
            'initial_odometer_reading' => $request->input('initialOdometerReading'),
            'current_odometer_reading' => $request->input('currentOdometerReading'),
            'inspection_date' => $request->input('inspectionDate'),
            'lst_service_odometer_reading' => $request->input('odometerReadingLastService'),
            'nxt_service_odometer-reading' => $request->input('nextServiceOdometerReading'),
            'odometer_reset' => false,
            'registration_date' => Carbon::parse($request->input('registrationDate')),
            'min_req_driving_license' => $request->input('requiredMinimumDrivingLicense'),
            'status' => VehicleStatusEnum::active,
            'sticker_registration_number' => $request->input('stickerRegistrationNumber') ?? "",
            'vehicle_charge_out_rate' => $request->input('chargeOutRate'),
            'white_book_serial' => trim(strtoupper($request->input('whiteBookSerial'))),
            'year_of_manufacture' => $request->input('yearOfManufacture'),
            'created_by' => $user->id,
            'created_name' => $user->name,
        ]);

        $this->fileUploadService->uploadFile($request,
            'front_view',
            'vehicleRegistration',
            $request->input('headerId'),
            'vehicleRegistration',
            'Front View',
            $user
        );

        $this->fileUploadService->uploadFile($request,
            'rear_view',
            'vehicleRegistration',
            $request->input('headerId'),
            'vehicleRegistration',
            'Back View',
            $user
        );

        $this->fileUploadService->uploadFile($request,
            'right_view',
            'vehicleRegistration',
            $request->input('headerId'),
            'vehicleRegistration',
            'Right View',
            $user
        );

        $this->fileUploadService->uploadFile($request,
            'left_view',
            'vehicleRegistration',
            $request->input('headerId'),
            'vehicleRegistration',
            'Left View',
            $user
        );

        $this->fileUploadService->uploadFile($request,
            'insurance_cover_note',
            'vehicleRegistration',
            $request->input('headerId'),
            'insurance',
            'Insurance Cover',
            $user
        );

        $this->fileUploadService->uploadFile($request,
            'motor_vehicle_certificate',
            'vehicleRegistration',
            $request->input('headerId'),
            'whitebooks',
            'Motor Vehicle Certificate',
            $user
        );

        DB::commit();

        return $model;
    }


}
