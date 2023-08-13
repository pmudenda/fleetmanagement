<?php

namespace App\Services\VehicleManagement\OnBoarding;

use App\Exceptions\VehicleOnBoardingException;
use App\Helpers\OnboardingStateHelper;
use App\Helpers\StatusHelper;
use App\Http\Requests\AssignmentPostRequest;
use App\Http\Requests\BodyDetailsPost;
use App\Http\Requests\ChassisDetailsPostRequest;
use App\Http\Requests\CostingDetailsPost;
use App\Http\Requests\EngineDetailsPost;
use App\Http\Requests\OnboardingVehicleAccessoryRequest;
use App\Http\Requests\VehicleHeaderRequest;
use App\Models\Common\OrganizationalUnit;
use App\Models\Reference\Area;
use App\Models\Settings\Accessory;
use App\Models\Settings\vehicle\ConfigVehicleBodyType;
use App\Models\Settings\vehicle\ConfigVehicleBrand;
use App\Models\Settings\vehicle\VehicleModel;
use App\Models\VehicleManagement\Assignment;
use App\Models\VehicleManagement\BodyAndWeightDetail;
use App\Models\VehicleManagement\ChassisDetail;
use App\Models\VehicleManagement\CostAndValuation;
use App\Models\VehicleManagement\EngineDetail;
use App\Models\VehicleManagement\VehicleAccessory;
use App\Models\VehicleManagement\VehicleHeader;
use App\Services\BarCodes\BarcodeGenerationService;
use App\Services\FileUploads\FileUploadService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OnBoardingService
{
    private FileUploadService $fileUploadService;
    private BarcodeGenerationService $codeService;

    public function __construct(FileUploadService        $fileUploadService,
                                BarcodeGenerationService $codeService)
    {
        $this->fileUploadService = $fileUploadService;
        $this->codeService = $codeService;
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
        $whiteBookSerial = $request->input('whiteBookSerial');
        $engineNumber = $request->input('engineNumber');

        if ($request->chassisDetailsId == 0) {
            $recordByRegistrationNumber = ChassisDetail::where('chassis_number', $chassisNumber)->first();

            if (!empty($recordByRegistrationNumber)) {
                throw new VehicleOnBoardingException('The Chassis Number ' . $chassisNumber . ' has already been registered');
            }

            $recordMotorVehicleCertificate = ChassisDetail::where('chassis_number', $whiteBookSerial)->first();

            if (!empty($recordMotorVehicleCertificate)) {
                throw new VehicleOnBoardingException('Vehicle with  White Book Serial ' . $whiteBookSerial . ' has already been registered');
            }

            $recordByEngineNumber = ChassisDetail::where('engine_number', $engineNumber)->first();

            if (!empty($recordByEngineNumber)) {
                throw new VehicleOnBoardingException('Vehicle with  Engine ' . $engineNumber . ' has already been registered');
            }
        }

        DB::beginTransaction();

        $model = ChassisDetail::updateOrCreate(
            [
                'vehicle_header_id' => $request->input('headerId'),
            ],
            [
                'chassis_number' => $chassisNumber,
                'date_on_road' => Carbon::parse($request->input('registrationDate')),
                'engine_number' => $request->input('engineNumber'),
                'initial_odometer_reading' => $request->input('initialOdometerReading'),
                'current_odometer_reading' => 0, //$request->input('currentOdometerReading'),
                'inspection_date' => Carbon::now()->format('Y-m-d'), //$request->input('inspectionDate'),
                'lst_service_odometer_reading' => 0, //$request->input('odometerReadingLastService')
                'nxt_service_odometer_reading' => 0, //$request->input('nextServiceOdometerReading')
                'odometer_reset' => false,
                'registration_date' => Carbon::parse($request->input('registrationDate')),
                'min_req_driving_license' => $request->input('requiredMinimumDrivingLicense'),
                'status' => StatusHelper::active(),
                'sticker_registration_number' => $request->input('stickerRegistrationNumber') ?? "N/A",
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
            'Vehicle Registration',
            'Front View',
            $user
        );

        $this->fileUploadService->uploadFile($request,
            'rear_view',
            'vehicleRegistration',
            $request->input('headerId'),
            'Vehicle Registration',
            'Back View',
            $user
        );

        $this->fileUploadService->uploadFile($request,
            'right_view',
            'vehicleRegistration',
            $request->input('headerId'),
            'Vehicle Registration',
            'Right View',
            $user
        );

        $this->fileUploadService->uploadFile($request,
            'left_view',
            'vehicleRegistration',
            $request->input('headerId'),
            'Vehicle Registration',
            'Left View',
            $user
        );

        $this->fileUploadService->uploadFile($request,
            'insurance_cover_note',
            'vehicleRegistration',
            $request->input('headerId'),
            'Vehicle Registration',
            'Insurance Cover',
            $user
        );

        $this->fileUploadService->uploadFile($request,
            'motor_vehicle_certificate',
            'vehicleRegistration',
            $request->input('headerId'),
            'Vehicle Registration',
            'Motor Vehicle Certificate',
            $user
        );
        try {
            self::generateBarCode($request->input('headerId'));
        } catch (\Exception $e) {
            Log::error($e);
        }


        $this->updateVehicleOnBoardingState($request->input('headerId'), OnboardingStateHelper::generalData);

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

        DB::beginTransaction();
        $user_unit_code = $request->input('user_unit');

        $organizationUnit = OrganizationalUnit::where('code_unit', $user_unit_code)->first();
        $registrationNumber = strtoupper(trim($request->input('registrationNumber')));
        $exitingRegistration = VehicleHeader::where('registration_number', $registrationNumber)->first();

        if (!empty($exitingRegistration)) {
            throw new VehicleOnBoardingException(
                'Vehicle with Registration Number ' . $registrationNumber . ' was already registered',
                0);
        }

        $brand = ConfigVehicleBrand::where('id', $request->input('brand'))->first();
        $vehicleModel = VehicleModel::where('id',
            $request->input('model'))->first();

        if (empty($vehicleModel)) {
            throw new Exception('Vehicle Model Not Found');
        }

        $bodyType = ConfigVehicleBodyType::where('id',
            $request->input('bodyType'))->first();

        if (empty($bodyType)) {
            throw new Exception('Vehicle Body Type Not Found');
        }

        $model = VehicleHeader::updateOrCreate(
            [
                'registration_number' => $registrationNumber,
            ],
            [
                'brand_guid' => $brand->id,
                'brand_name' => $brand->name,
                'model_guid' => $vehicleModel->id,
                'model_name' => $vehicleModel->model_name,
                'model_code' => $vehicleModel->model_code,
                'body_type_guid' => $bodyType->id,
                'body_type_name' => $bodyType->body_type_name,
                'business_unit_code' => trim($organizationUnit->code_unit),
                'business_unit_name' => trim($organizationUnit->description),
                'location_code' => 'N/A',
                'location_name' => strtoupper(trim($request->input('vehicleLocation'))),
                'created_by' => $user->id,
                'created_name' => $user->name,
                'on_boarding_status' => StatusHelper::PendingGeneralDataEntry(),
                'status' => StatusHelper::vehicleInactive(),
                'registration_type' => $request->registration_type
            ]);

        DB::commit();
        return $model;
    }

    /**
     * @param EngineDetailsPost $request
     * @return mixed
     * @throws VehicleOnBoardingException
     */
    public function processEngineDetails(EngineDetailsPost $request): EngineDetail
    {
        $user = auth()->user();
        DB::beginTransaction();
        $model = EngineDetail::updateOrCreate(
            [
                'vehicle_header_id' => $request->input('headerId'),
            ],
            [
                'actual_engine_power' => $request->input('actualEnginePower'),
                'claimed_engine_power' => $request->input('claimedEnginePower'),
                'engine_brand' => $request->input('engineBrand'),
                'engine_capacity' => $request->input('engineCapacity'),
                'engine_type' => $request->input('engineType'),
                'fuel_allocation' => $request->input('fuelAllocation') ?? 10,
                'fuel_consumption' => $request->input('fuelConsumption'),
                'fuel_types' => $request->input('fuelTypes'),
                'number_of_cylinders' => $request->input('numberOfCylinders'),
                'tank_capacity' => $request->input('tank_capacity'),
                'sub_tank_capacity' => $request->input('sub_tank_capacity'),
                'transmission_type' => $request->input('transmission_type'),
                'battery_brand' => $request->input('batteryBrand'),
                'battery_size' => $request->input('batterySize'),
                'num_batteries' => $request->input('numberOfBatteries'),
                'battery_power' => $request->input('batteryPower'),
                'front_tyre_size' => $request->input('frontTyreSize'),
                'number_of_tyres' => $request->input('numberOfTyres'),
                'rear_tyre_size' => $request->input('rearTyreSize'),
                'tyre_brand' => $request->input('tyreBrand'),
                'created_by' => $user->id,
                'created_name' => $user->name
            ]);

        $this->updateVehicleOnBoardingState($request->input('headerId'), OnboardingStateHelper::technicalData);

        DB::commit();
        return $model;
    }

    /**
     * @param CostingDetailsPost $request
     * @return mixed
     * @throws VehicleOnBoardingException
     */
    public function processCostingDetails(CostingDetailsPost $request): CostAndValuation
    {
        $user = auth()->user();

        DB::beginTransaction();

        $purchase_order_document = '';
        if ($request->purchaseOrderDocument) {
            $uploadPath = $this->fileUploadService->uploadFile($request,
                'purchaseOrderDocument',
                'vehicleRegistration',
                $request->input('headerId'),
                'vehicleRegistration',
                'Purchase Order',
                $user
            );

            $purchase_order_document = empty($uploadPath) ? '' : $uploadPath[0]['path'];
        }

        $model = CostAndValuation::updateOrCreate(
            [
                'vehicle_header_id' => $request->input('headerId'),
            ],
            [
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
                'purchase_order_document' => $purchase_order_document
            ]);

        $this->updateVehicleOnBoardingState($request->input('headerId'), OnboardingStateHelper::costing);

        DB::commit();

        return $model;
    }

    /**
     * @param BodyDetailsPost $request
     * @return mixed
     * @throws VehicleOnBoardingException
     */
    public function processingBodyDetails(BodyDetailsPost $request): mixed
    {
        $user = auth()->user();
        DB::beginTransaction();
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

        $this->updateVehicleOnBoardingState($request->input('headerId'), OnboardingStateHelper::bodyDetails);

        $model = BodyAndWeightDetail::updateOrCreate(
            [
                'vehicle_header_id' => $request->input('headerId'),
            ],
            $data);
        DB::commit();

        return $model;
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
        DB::beginTransaction();
        $costCenterParts = explode(":", $request->get('costCenter'));
        $businessUnitParts = explode(":", $request->get('businessUnit'));
        $code_center_code = $costCenterParts[0];
        $code_center_name = $costCenterParts[1];

        $bu_code = $businessUnitParts[0];
        $bu_name = $businessUnitParts[1];

        $businessArea = Area::where('area', '=', trim($request->input('businessArea')))
            //->where('type', '=', 'businessAreas')
            ->first();

        if (!$businessArea) {
            throw new VehicleOnBoardingException("Invalid Business Area Selected ", 0);
        }

        $responsibleId = $request->input('responsibleHODId') ?? $request->input('vehicleHolderId');
        $responsibleName = $request->input('responsibleHOD') ?? $request->input('vehicleHolder');

        //$responsibleName = $request->input('vehicleHolder');
        //$responsibleId = $request->input('vehicleHolderId');

        $data = [
            'vehicle_header_id' => $request->input('headerId'),
            'business_area_code' => $businessArea->area,
            'directorate' => $request->input('directorate'),
            'cost_center' => $code_center_code,
            'responsible_head_id' => $responsibleId,
            'responsible_head_name' => $responsibleName,
            'isPoolVehicle' => $request->input('isPoolVehicle'),
            'isTeamAssigned' => $request->get('isPoolVehicle') == 'Y',
            'mileageExempt' => $request->input('isMileageExempt'),
            'created_by' => $user->id,
            'created_name' => $user->name,
            'cost_center_name' => $code_center_name,
            'business_unit' => $bu_code,
            'business_unit_name' => $bu_name,
            'business_area_name' => $businessArea->description,
            'assignment_state' => StatusHelper::active()
        ];

        $this->updateVehicleOnBoardingState($request->input('headerId'), OnboardingStateHelper::assignment);

        $model = Assignment::updateOrCreate(
            [
                'vehicle_header_id' => $request->input('headerId'),
            ],
            $data);

        DB::commit();

        return $model;
    }

    public function generateBarCode($headerId): string
    {
        $record = VehicleHeader::find($headerId);
        $barCodeParams = [
            'text' => $record->registration_number,
            'size' => 50,
            'orientation' => 'horizontal',
            'code_type' => 'code39',
            'print' => true,
            'sizeFactor' => 2,
            'filename' => $record->registration_number,
            'filePath' => 'vehicleBarcodes',
            'fileType' => '.jpeg',
        ];

        $barCodePath = $this->codeService->renderBarcode(
            $barCodeParams["text"],
            $barCodeParams['size'],
            $barCodeParams['orientation'],
            $barCodeParams['code_type'], // code_type : code128,code39,code128b,code128a,,
            $barCodeParams['print'],
            $barCodeParams['sizeFactor'],
            $barCodeParams['filename'] . $barCodeParams['fileType'],
            $barCodeParams['filePath'],
            $barCodeParams['fileType'],
        )->filename($barCodeParams['filename'] . $barCodeParams['fileType']);

        /*DB::table('VM_VEHICLE_HEADER')
            ->where('id', '=', $record->id)
            ->update(['barcode' => $barCodePath]);*/

        $record->barcode = $barCodePath;
        $record->save();
        return $barCodePath;
    }

    /**
     * @param int $headerId vehicle identifier
     * @param string $stage
     * @return void
     * @throws VehicleOnBoardingException
     */
    public function updateVehicleOnBoardingState(int $headerId, string $stage): void
    {
        $vehicleHeader = VehicleHeader::find($headerId);

        if (!$vehicleHeader) {
            throw  new VehicleOnBoardingException("OnboardingRecord Not Found", 0);
        }

        $onboardingStatus = "";
        if ($stage === OnboardingStateHelper::assignment) {
            $onboardingStatus = StatusHelper::onboardingComplete();
            $vehicleHeader->status = StatusHelper::active();
        } else if (OnboardingStateHelper::generalData) {
            $onboardingStatus = StatusHelper::PendingTechnicalDataEntry();
        } else if (OnboardingStateHelper::technicalData) {
            $onboardingStatus = StatusHelper::PendingAccessoriesCheckin();
        } else if (OnboardingStateHelper::accessoriesCheckin) {
            $onboardingStatus = StatusHelper::PendingCostingDataEntry();
        } else if (OnboardingStateHelper::costing) {
            $onboardingStatus = StatusHelper::PendingAssignment();
        }

        $vehicleHeader->on_boarding_status = $onboardingStatus;
        $vehicleHeader->save();
    }

    /**
     * @throws VehicleOnBoardingException
     */
    public function processAccessory(OnboardingVehicleAccessoryRequest $request): array
    {
        $headerId = $request->get('headerId');

        $accessoryNames = Accessory::where('status', '=', StatusHelper::active())
            ->get();
        foreach ($accessoryNames as $accessoryName) {
            $accessoryCode = $accessoryName->code;

            $response = $request->get($accessoryCode);
            $remarks = $request->get('COMMENT_' . $accessoryCode);

            VehicleAccessory::create(
                [
                    'vehicle_header_id' => $headerId,
                    'name' => $accessoryName->name,
                    'code' => $accessoryCode,
                    'remarks' => $remarks,
                    'is_present' => $response
                ]
            );
        }


        $this->updateVehicleOnBoardingState($request->input('headerId'), OnboardingStateHelper::accessoriesCheckin);

        return $request->all();
    }

}
