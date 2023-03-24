<?php

use App\Models\configurations\vehicle\ConfigVehicleBodyType;
use App\Models\configurations\vehicle\ConfigVehicleBrand;
use App\Models\configurations\vehicle\ConfigVehicleModel;
use App\Models\vehiclemanagement\Assignment;
use App\Models\vehiclemanagement\BodyAndWeightDetail;
use App\Models\vehiclemanagement\ChassisDetail;
use App\Models\vehiclemanagement\CostAndValuation;
use App\Models\vehiclemanagement\EngineDetail;
use App\Models\vehiclemanagement\VehicleHeader;
use App\Services\FileUploads\VehicleImageFileUploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//, 'middleware' => 'auth'
Route::group(['prefix' => 'vehicle-management'], function () {

    Route::get('/register', function () {
        return view('vehicleManagement.register.index');
    })->name('new.vehicle');


    Route::get('/vehicle/list', function () {
        $vehicleList =  VehicleHeader::get();
        return view('vehicleManagement.vehicleList')
            ->with(compact('vehicleList'));
    })->name('vehicles.list');



    Route::get('/vehicles', function (Request $request) {

        return view('vehicleManagement.vehicleList');
    })->name('vehicle.edit');

    /*VEHICLES*/
    Route::post('vehicles', function (Request $request) {
        try {
            $user = auth()->user();
            $docType = $request->input('doctype');
            $model = null;
            if ($docType === 'VehicleHeader') {
                // validate
                $buParts = explode('=>', $request->input('userUnit'));

                $registrationNumber = strtoupper(trim($request->input('registrationNumber')));
                $exitingRegistration = VehicleHeader::where('registration_number', $registrationNumber)->first();

                if (!empty($exitingRegistration)) {
                    return response()->json([
                        'state' => 'failure',
                        'payload' => (object)[],
                        'message' => 'Vehicle with Registration Number ' . $registrationNumber . ' was already registered'
                    ]);
                }

                $brand = ConfigVehicleBrand::where('guid', $request->input('brand'))->first();
                $vehicleModel = ConfigVehicleModel::where('model_guid',
                    $request->input('model'))->first();
                $bodyType = ConfigVehicleBodyType::where('guid',
                    $request->input('bodyType'))->first();

                $model = VehicleHeader::create([
                    'brand_guid' => $brand->guid,
                    'brand_name' => $brand->name,
                    'model_guid' => $vehicleModel->model_guid,
                    'model_name' => $vehicleModel->model_name,
                    'model_code' => $vehicleModel->model_code,
                    'body_type_guid' => $bodyType->guid,
                    'body_type_name' => $bodyType->body_type_name,
                    'registration_number' => $registrationNumber,
                    'business_unit_code' => trim($buParts[0]),
                    'business_unit_name' => trim($buParts[1]),
                    'location_code' => '',
                    'location_name' => strtoupper(trim($request->input('vehicleLocation'))),
                    'created_by' => $user->id,
                    'created_name' => $user->name
                ]);

            } else if ($docType === 'ChassisDetails') {

                $model = ChassisDetail::create([
                    'vehicle_header_id' => $request->input('headerId'),
                    'chassis_number' => $request->input('chassisNumber'),
                    'date_on_road' => Carbon::parse($request->input('dateOnRoad')),
                    'engine_number' => $request->input('engineNumber'),
                    'initial_odometer_reading' => $request->input('initialOdometerReading'),
                    'current_odometer_reading' => $request->input('currentOdometerReading'),
                    'inspection_date' => $request->input('inspectionDate'),
                    'lst_service_odometer_reading' => $request->input('odometerReadingLastService'),
                    'nxt_service_odometer-reading' => $request->input('nextServiceOdometerReading'),
                    'odometer_reset' => $request->input('odometerReset') ?? false,
                    'registration_date' => Carbon::parse($request->input('registrationDate')),
                    'min_req_driving_license' => $request->input('requiredMinimumDrivingLicense'),
                    'status' => $request->input('status'),
                    'sticker_registration_number' => $request->input('stickerRegistrationNumber') ?? "",
                    'vehicle_charge_out_rate' => $request->input('chargeOutRate'),
                    'white_book_serial' => trim(strtoupper($request->input('whiteBookSerial'))),
                    'year_of_manufacture' => $request->input('yearOfManufacture'),
                    'created_by' => $user->id,
                ]);
            } else if ($docType === 'EngineDetails') {

                //$request->input('headerId');;;;;;;;;;

                $model = EngineDetail::create([
                    'vehicle_header_id' => $request->input('headerId'),
                    'actual_engine_power' => $request->input('actualEnginePower'),
                    'claimed_engine_power' => $request->input('claimedEnginePower'),
                    'engine_brand' => $request->input('engineBrand'),
                    'engine_capacity' => $request->input('engineCapacity'),
                    'engine_type' => $request->input('engineType'),
                    'fuel_allocation' => $request->input('fuelAllocation'),
                    'fuel_consumption' => $request->input('fuelConsumption'),
                    'fuel_types' => $request->input('fuelTypes'),
                    'number_of_cylinders' => $request->input('numberOfCylinders'),
                    'tank_capacity' => $request->input('tank_capacity'),
                    'sub_tank_capacity' => $request->input('sub_tank_capacity'),
                    'transmission_type' => $request->input('transmission_type'),
                    'battery_brand' => $request->input('batteryBrand'),
                    'battery_size' => $request->input('batterySize'),
                    'battery_power' => $request->input('batteryPower'),
                    'front_tyre_size' => $request->input('frontTyreSize'),
                    'number_of_tyres' => $request->input('numberOfTyres'),
                    'rear_tyre_size' => $request->input('rearTyreSize'),
                    'tyre_brand' => $request->input('tyreBrand'),
                ]);

            } else if ($docType === 'CostingDetails') {
                $model = CostAndValuation::create([
                    'vehicle_header_id' => $request->input('headerId'),
                    'assetNumber' => $request->input('assetNumber'),
                    'bookValue' => $request->input('bookValue'),
                    'costOfLicense' => $request->input('costOfLicense'),
                    'costPrice' => $request->input('costPrice'),
                    'premium' => $request->input('premium'),
                    'supplierName' => $request->input('supplierName'),
                    'yearOfPurchase' => $request->input('yearOfPurchase'),
                    'created_by' => $user->id,
                    'created_name' => $user->name,
                ]);

            } else if ($docType === 'BodyDetails') {

                $data = [
                    'vehicle_header_id' => $request->input('headerId'),
                    'height' => $request->input('height'),
                    'length' => $request->input('length'),
                    'width' => $request->input('width'),
                    'seatCapFront' => $request->input('seatCapFront'),
                    'seatCapRear' => $request->input('seatCapRear'),
                    'volumeOfBootTanker' => $request->input('volumeOfBootTanker'),
                    'numberOfSeats' => $request->input('numberOfSeats'),
                    'distanceAxle1' => $request->input('distanceAxle1'),
                    'distanceAxle2' => $request->input('distanceAxle2'),
                    'distanceAxle3' => $request->input('distanceAxle3'),
                    'distanceAxle4' => $request->input('distanceAxle4'),
                    'tareWeight' => $request->input('tareWeight'),
                    'grossWeight' => $request->input('grossWeight'),
                    'trailerWeight2' => $request->input('trailerWeight2'),
                    'trailerWeight3' => $request->input('trailerWeight3'),
                    'trailerWeight4' => $request->input('trailerWeight4'),
                ];

                $model = BodyAndWeightDetail::create($data);

            } elseif ($docType === 'AssignmentDetails') {

                $data = [
                    'vehicle_header_id' => $request->input('headerId'),
                    'businessArea' => $request->input('businessArea'),
                    'directorate' => $request->input('directorate'),
                    'costCenter' => $request->input('costCenter'),
                    'superVisorStaffNumber' => $request->input('operatorSupervisorStaffNumber'),
                    'superVisorName' => $request->input('superVisorName'),
                    'operatorStaffNumber' => $request->input('operatorStaffNumber'),
                    'operatorName' => $request->input('operatorName'),
                    'casualStaffNumber' => $request->input('casual_staff_number'),
                    'casualStaffName' => $request->input('casual_staff_name'),
                    'isPoolVehicle' => $request->input('isPoolVehicle'),
                    'isTeamAssigned' => $request->input('isTeamAssigned'),
                    'mileageExempt' => $request->input('isMileageExempt'),
                    'created_by' => $user->id,
                    'created_name' => $user->name
                ];

                $model = Assignment::create($data);

            }

            else if ($docType === 'CompletionDetails'){

                VehicleImageFileUploadService::uploadFile($request,
                    'front_view',
                    'vehicleRegistration',
                    $request->input('headerId'),
                    'vehicleRegistration',
                    'Front View'
                );

                VehicleImageFileUploadService::uploadFile($request,
                    'rear_view',
                    'vehicleRegistration',
                    $request->input('headerId'),
                    'vehicleRegistration',
                    'Back View'
                );

                VehicleImageFileUploadService::uploadFile($request,
                    'right_view',
                    'vehicleRegistration',
                    $request->input('headerId'),
                    'vehicleRegistration',
                    'Right View'
                );

                VehicleImageFileUploadService::uploadFile($request,
                    'left_view',
                    'vehicleRegistration',
                    $request->input('headerId'),
                    'vehicleRegistration',
                    'Left View'
                );

                $model = [];
            }

            return response()->json([
                'state' => 'success',
                'request' => $request->all(),
                'payload' => $model,
                'message' => 'Request Submitted Successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'state' => 'failure',
                'payload' => (object)[],
                'message' => 'Sorry, some errors were detected while processing your request, please try again later.'
            ]);
        }
    })->name('api.vehicle.new');


    Route::get('/insurancelist', function () {
        return view('VehicleManagement.insurancelist');
    })->name('insurancelist');

    Route::get('/legaldocumentlist', function () {
        return view('VehicleManagement.insurancelist');
    })->name('legaldocumentlist');




});
