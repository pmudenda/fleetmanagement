<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Enums\VehicleStatusEnum;
use App\Exceptions\VehicleOnBoardingException;
use App\Http\Controllers\Controller;
use App\Models\configurations\vehicle\ConfigVehicleBodyType;
use App\Models\configurations\vehicle\ConfigVehicleBrand;
use App\Models\configurations\vehicle\ConfigVehicleModel;
use App\Models\general\OrganizationalUnits;
use App\Models\vehiclemanagement\Assignment;
use App\Models\vehiclemanagement\BodyAndWeightDetail;
use App\Models\vehiclemanagement\ChassisDetail;
use App\Models\vehiclemanagement\CostAndValuation;
use App\Models\vehiclemanagement\EngineDetail;
use App\Models\vehiclemanagement\VehicleHeader;
use App\Services\FileUploads\VehicleImageFileUploadService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VehicleOnBoardingController extends Controller
{

    public function __invoke(Request $request): JsonResponse
    {
        try {

            $docType = $request->input('doctype');

            Log::debug('Request Doc Type '+ $docType);

            $model = match ($docType) {
                'VehicleHeader' => $this->processVehicleHeaderInformation($request),
                'ChassisDetails' => $this->processChassisDetails($request),
                'EngineDetails' => $this->processEngineDetails($request),
                'CostingDetails' => $this->processCostingDetails($request),
                'BodyDetails' => $this->processingBodyDetails($request),
                'AssignmentDetails' => $this->processAssignmentDetails($request),
                default => null,
            };

            return response()->json([
                'state' => 'success',
                'request' => $request->all(),
                'payload' => $model,
                'message' => 'Request Submitted Successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = 'Sorry, some errors were detected while processing your request, please try again later.';
            if ($e instanceof VehicleOnBoardingException) {
                $message = $e->getMessage();
            }
            return response()->json([
                'state' => 'failure',
                'payload' => (object)[],
                'message' => $message
            ]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws VehicleOnBoardingException
     * @throws Exception
     */
    public function processVehicleHeaderInformation(Request $request): mixed
    {

        $validator = $this->validateRequest($request, [
            'brand',
            'user_unit',
            'model',
            'bodyType',
            'registrationNumber',
        ]);

        if (!$validator->passes()) {
            return response()->json(
                [
                    'state' => 'error',
                    'errors' => $validator->errors()->all()
                ]);
        }

        $user = auth()->user();
        // validate

        $user_unit_code = $request->input('user_unit');

        //if ($request->has('user_unit'))
        $organizationUnit = OrganizationalUnits::where('code_unit', $user_unit_code)->first();
        $registrationNumber = strtoupper(trim($request->input('registrationNumber')));
        $exitingRegistration = VehicleHeader::where('registration_number', $registrationNumber)->first();

        if (!empty($exitingRegistration)) {
            throw new VehicleOnBoardingException(
                'Vehicle with Registration Number ' . $registrationNumber . ' was already registered',
                'REG');
        }

        $brand = ConfigVehicleBrand::where('guid', $request->input('brand'))->first();
        $vehicleModel = ConfigVehicleModel::where('model_guid',
            $request->input('model'))->first();

        if (empty($vehicleModel)) {
            throw new Exception('Vehicle Model Not Found');
        }

        $bodyType = ConfigVehicleBodyType::where('guid',
            $request->input('bodyType'))->first();

        if (empty($bodyType)) {
            throw new Exception('Vehicle Body Type Not Found');
        }

        return VehicleHeader::create([
            'brand_guid' => $brand->guid,
            'brand_name' => $brand->name,
            'model_guid' => $vehicleModel->model_guid,
            'model_name' => $vehicleModel->model_name,
            'model_code' => $vehicleModel->model_code,
            'body_type_guid' => $bodyType->guid,
            'body_type_name' => $bodyType->body_type_name,
            'registration_number' => $registrationNumber,
            'business_unit_code' => trim($organizationUnit->code_unit),
            'business_unit_name' => trim($organizationUnit->description),
            'location_code' => '',
            'location_name' => strtoupper(trim($request->input('vehicleLocation'))),
            'created_by' => $user->id,
            'created_name' => $user->name
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws VehicleOnBoardingException
     */
    public function processChassisDetails(Request $request): array
    {
        // validate

        $validator = $this->validateRequest($request, [
            'chassisNumber',
            'registrationDate',
            'engineNumber',
            'initialOdometerReading',
            'nextServiceOdometerReading',
            'requiredMinimumDrivingLicense',
            'whiteBookSerial',
            'yearOfManufacture',
        ]);

        Log::info('Validation State');

        if ($validator->fails()) {
            throw  new VehicleOnBoardingException($validator->errors()->all());
        }

        $user = auth()->user();

        $chassisNumber = $request->input('chassisNumber');

        $exitingRegistration = ChassisDetail::where('chassis_number', $chassisNumber)->first();

        if (!empty($exitingRegistration)) {
            throw new VehicleOnBoardingException('The Chassis Number you have provided has already been registered');
        }

        $isValid = $this->validateUploads($request, []);
        if (!empty($isValid)) {
            throw new VehicleOnBoardingException('Required documentation not attached');
        }

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

        VehicleImageFileUploadService::uploadFile($request,
            'front_view',
            'vehicleRegistration',
            $request->input('headerId'),
            'vehicleRegistration',
            'Front View'
        );
        //motor_vehicle_certificate
        //insurance_cover_note

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

        return $model;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function processEngineDetails(Request $request): mixed
    {
        $user = auth()->user();

        return EngineDetail::create([
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
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function processCostingDetails(Request $request): mixed
    {
        $user = auth()->user();
        $validator = $this->validateRequest($request, [
            'assetNumber',
            'bookValue',
            'costOfLicense',
            'costPrice',
            'premium',
            'yearOfPurchase',
        ]);

        if (!$validator->passes()) {
            return response()->json(
                [
                    'state' => 'error',
                    'errors' => $validator->errors()->all()
                ]);
        }
        return CostAndValuation::create([
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
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function processingBodyDetails(Request $request): mixed
    {
        $user = auth()->user();
        $validator = $this->validateRequest($request, [
            'height',
            'length',
            'width',
            'numberOfSeats',
            'tareWeight',
            'grossWeight',
        ]);

        if (!$validator->passes()) {
            return response()->json(
                [
                    'state' => 'error',
                    'errors' => $validator->errors()->all()
                ]);
        }

        $data = [
            'vehicle_header_id' => $request->input('headerId'),
            'height' => $request->input('height'),
            'length' => $request->input('length'),
            'width' => $request->input('width'),
            'seatCapFront' => 0,
            'seatCapRear' => 0,
            'volumeOfBootTanker' => 0,
            'numberOfSeats' => $request->input('numberOfSeats'),
            'distanceAxle1' => $request->has('distanceAxle1') ? $request->input('distanceAxle1') : 0,
            'distanceAxle2' => $request->has('distanceAxle2') ? $request->input('distanceAxle2') : 0,
            'distanceAxle3' => $request->has('distanceAxle3') ? $request->input('distanceAxle3') : 0,
            'distanceAxle4' => $request->has('distanceAxle4') ? $request->input('distanceAxle4') : 0,
            'tareWeight' => $request->input('tareWeight'),
            'grossWeight' => $request->input('grossWeight'),
            'trailerWeight2' => 0,
            'trailerWeight3' => 0,
            'trailerWeight4' => 0,
            'created_by' => $user->id,
            'created_name' => $user->name,
        ];

        return BodyAndWeightDetail::create($data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function processAssignmentDetails(Request $request): mixed
    {
        // marks completion of on-boarding
        $user = auth()->user();


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

        return Assignment::create($data);
    }


    /**
     * @param Request $request
     * @param $validationFields
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateRequest(Request $request, $validationFields): \Illuminate\Contracts\Validation\Validator
    {
        $rules = [];
        $messages = [];
        foreach ($validationFields as $validationField) {
            $rules = [$validationField => ['required']];
            $messages = [$validationField => 'You have not provided valid data for ' . $validationField];
        }

        // request, rules, messages
        return Validator::make(
            $request->all(),
            $rules, $messages
        );

    }

    public function validateUploads(Request $request, $validationFields): bool
    {
        /* $rules = [];
         $messages = [];
         foreach ($validationFields as $validationField) {
             $rules = [$validationField => ['required']];
             $messages = [$validationField => 'You have not provided valid data for ' . $validationField];
         }*/

        $validator = Validator::make(
            $request->all(),
            [
                'image_file.*_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff'
            ],
            [
                'image_file.*.required' => 'Please upload an image',
                'image_file.*.mimes' => 'Only =jpg,jpeg,png,bmp,tif,tiff images are allowed',
            ]
        );

        /*if ($validator->fails()) {
            $messages = $validator->messages();
            return Redirect::to('/')->with('message', 'Your erorr message');
        }*/

        return $validator->passes();

    }

}
