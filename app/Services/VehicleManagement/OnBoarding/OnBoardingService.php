<?php

namespace App\Services\VehicleManagement\OnBoarding;

use App\Enums\VehicleStatusEnum;
use App\Exceptions\VehicleOnBoardingException;
use App\Helpers\StatusHelper;
use App\Http\Requests\AssignmentPostRequest;
use App\Http\Requests\BodyDetailsPost;
use App\Http\Requests\ChassisDetailsPostRequest;
use App\Http\Requests\CostingDetailsPost;
use App\Http\Requests\EngineDetailsPost;
use App\Http\Requests\VehicleHeaderRequest;
use App\Models\configurations\vehicle\ConfigVehicleBodyType;
use App\Models\configurations\vehicle\ConfigVehicleBrand;
use App\Models\configurations\vehicle\ConfigVehicleModel;
use App\Models\general\BusinessAreas;
use App\Models\general\File;
use App\Models\general\OrganizationalUnits;
use App\Models\vehiclemanagement\Assignment;
use App\Models\vehiclemanagement\BodyAndWeightDetail;
use App\Models\vehiclemanagement\ChassisDetail;
use App\Models\vehiclemanagement\CostAndValuation;
use App\Models\vehiclemanagement\EngineDetail;
use App\Models\vehiclemanagement\VehicleHeader;
use App\Services\FileUploads\FileUploadService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class OnBoardingService
{
    private FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }


    public function getVehicleDetails($ref): object|null
    {
        return DB::table('VM_VEHICLE_HEADER')->
        where('.VM_VEHICLE_HEADER.id', '=', $ref)
            ->leftJoin('VM_ENGINE_DETAILS', 'VM_VEHICLE_HEADER.id', '=', 'VM_ENGINE_DETAILS.vehicle_header_id')
            ->leftJoin('VM_ASSIGNMENTS', 'VM_VEHICLE_HEADER.id', '=', 'VM_ASSIGNMENTS.vehicle_header_id')
            ->leftJoin('VM_CHASSIS_DETAILS', 'VM_VEHICLE_HEADER.id', '=', 'VM_CHASSIS_DETAILS.vehicle_header_id')
            ->leftJoin('VM_COST_AND_VALUATIONS', 'VM_VEHICLE_HEADER.id', '=', 'VM_COST_AND_VALUATIONS.vehicle_header_id')
            ->leftJoin('VM_BODY_AND_WEIGHT_DETAILS', 'VM_VEHICLE_HEADER.id', '=', 'VM_BODY_AND_WEIGHT_DETAILS.vehicle_header_id')
            ->select('VM_VEHICLE_HEADER.id as headerId',
                'VM_VEHICLE_HEADER.*',
                'VM_ASSIGNMENTS.id as assignmentId', 'VM_ASSIGNMENTS.*',
                'VM_ENGINE_DETAILS.id as engineDetailsId', 'VM_ENGINE_DETAILS.*',
                'VM_CHASSIS_DETAILS.id as chassisDetailsId',
                'VM_CHASSIS_DETAILS.*',
                'VM_COST_AND_VALUATIONS.id as costAndValuationId',
                'VM_COST_AND_VALUATIONS.*',
                'VM_BODY_AND_WEIGHT_DETAILS.id as weightDetailsId',
                'VM_BODY_AND_WEIGHT_DETAILS.*')
            ->first();
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


    /**
     * @param VehicleHeaderRequest $request
     * @return mixed
     * @throws VehicleOnBoardingException
     * @throws Exception
     */
    public function processVehicleHeaderInformation(VehicleHeaderRequest $request): mixed
    {
        $user = auth()->user();

        $user_unit_code = $request->input('user_unit');

        //if ($request->has('user_unit'))
        $organizationUnit = OrganizationalUnits::where('code_unit', $user_unit_code)->first();
        $registrationNumber = strtoupper(trim($request->input('registrationNumber')));
        $exitingRegistration = VehicleHeader::where('registration_number', $registrationNumber)->first();

        if (!empty($exitingRegistration)) {
            throw new VehicleOnBoardingException(
                'Vehicle with Registration Number ' . $registrationNumber . ' was already registered',
                0);
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
            'created_name' => $user->name,
            'on_boarding_status' => StatusHelper::PendingVerification(),
            'statue' => StatusHelper::new(),
            'registration_type' => $request->registration_type
        ]);
    }

    /**
     * @param EngineDetailsPost $request
     * @return mixed
     */
    public function processEngineDetails(EngineDetailsPost $request): mixed
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
     * @param CostingDetailsPost $request
     * @return mixed
     */
    public function processCostingDetails(CostingDetailsPost $request): mixed
    {
        $user = auth()->user();

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
     * @param BodyDetailsPost $request
     * @return mixed
     */
    public function processingBodyDetails(BodyDetailsPost $request): mixed
    {
        $user = auth()->user();

        $data = [
            'vehicle_header_id' => $request->input('headerId'),
            'height' => $request->input('height'),
            'length' => $request->input('length'),
            'width' => $request->input('width'),
            'seatCapFront' => 0,
            'seatCapRear' => 0,
            'volumeOfBootTanker' => 0,
            'numberOfSeats' => $request->input('numberOfSeats') ?? $request->get('seatCapFront'),
            'distanceAxle1' => $request->get('distanceAxle1') ?? 0,
            'distanceAxle2' => $request->get('distanceAxle2') ?? 0,
            'distanceAxle3' => $request->get('distanceAxle3') ?? 0,
            'distanceAxle4' => $request->get('distanceAxle4') ?? 0,
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
     * @param AssignmentPostRequest $request
     * @return mixed
     * @throws VehicleOnBoardingException
     */
    public function processAssignmentDetails(AssignmentPostRequest $request): mixed
    {
        // marks completion of on-boarding
        $user = auth()->user();


        $costCenterParts = explode(":", $request->get('costCenter'));
        $businessUnitParts = explode(":", $request->get('businessUnit'));
        $code_center_code = $costCenterParts[0];
        $code_center_name = $costCenterParts[1];

        $bu_code = $businessUnitParts[0];
        $bu_name = $businessUnitParts[1];

        $businessArea = BusinessAreas::where('code', '=', trim($request->input('businessArea')))->first();

        if (!$businessArea) {
            throw new VehicleOnBoardingException("Invalid Business Area", 0);
        }

        $data = [
            'vehicle_header_id' => $request->input('headerId'),
            'business_area_code' => $businessArea->code,
            'directorate' => $request->input('directorate'),
            'cost_center' => $code_center_code,
            'responsible_head_id' => $request->input('responsibleHODId') ?? $request->input('vehicleHolderId'),
            'responsible_head_name' => $request->input('responsibleHOD') ?? $request->input('vehicleHolder'),
            'isPoolVehicle' => $request->input('isPoolVehicle'),
            'isTeamAssigned' => $request->get('isPoolVehicle') == 'Y',
            'mileageExempt' => $request->input('isMileageExempt'),
            'created_by' => $user->id,
            'created_name' => $user->name,
            'cost_center_name' => $code_center_name,
            'business_unit' => $bu_code,
            'business_area_name' => $businessArea->name
        ];

        $vehicleHeader = VehicleHeader::find($request->input('headerId'));

        if (!$vehicleHeader) {
            throw  new VehicleOnBoardingException("OnboardingRecord Not Found", 0);
        }

        $vehicleHeader->on_boarding_status = StatusHelper::onboardingComplete();
        $vehicleHeader->save();

        return Assignment::create($data);
    }

    public function getVehicleDocuments(mixed $reference)
    {
        return File::where('reference_number', "=", $reference)
            ->where('status', '=', '01')
            ->get();
    }


}
