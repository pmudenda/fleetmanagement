<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Exceptions\VehicleOnBoardingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChassisDetailsPostRequest;
use App\Models\configurations\vehicle\ConfigVehicleBodyType;
use App\Models\configurations\vehicle\ConfigVehicleBrand;
use App\Models\configurations\vehicle\ConfigVehicleModel;
use App\Models\general\OrganizationalUnits;
use App\Models\vehiclemanagement\Assignment;
use App\Models\vehiclemanagement\BodyAndWeightDetail;
use App\Models\vehiclemanagement\CostAndValuation;
use App\Models\vehiclemanagement\EngineDetail;
use App\Models\vehiclemanagement\VehicleHeader;
use App\Services\VehicleManagement\OnBoarding\OnBoardingService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VehicleOnBoardingController extends Controller
{

    private OnBoardingService $onBoardingService;

    public function __construct(OnBoardingService $onBoardingService)
    {
        $this->onBoardingService = $onBoardingService;
    }

    public function store(Request $request): JsonResponse
    {
        try {

            $docType = $request->input('doctype');

            Log::debug('Request Doc Type ' . $docType);

            $model = match ($docType) {
                'VehicleHeader' => $this->processVehicleHeaderInformation($request),
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
     * @param ChassisDetailsPostRequest $request
     * @return JsonResponse
     */
    public function storeChassisDetails(ChassisDetailsPostRequest $request): JsonResponse
    {
        try {
            $docType = $request->input('doctype');
            Log::debug('Request Doc Type ' . $docType);
            $model = $this->onBoardingService->processChassisDetails($request);
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
            'created_name' => $user->name
        ]);
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
            'seatCapFront',
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
            'numberOfSeats' => $request->input('numberOfSeats') ?? $request->get('seatCapFront'),
            'distanceAxle1' => $request->get('distanceAxle1') ??  0,
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
     * @param Request $request
     * @return mixed
     */
    public function processAssignmentDetails(Request $request): mixed
    {

        $validator = $this->validateRequest($request, [
            'businessArea',
            'directorate',
            'costCenter',
            'isPoolVehicle',
            'isMileageExempt',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'state' => 'error',
                    'errors' => $validator->errors()->all()
                ]);
        }

        if($request->input('isPoolVehicle') == 'Y'){
            $validator = $this->validateRequest($request, [
                'responsibleHOD',
                'responsibleHODId',
            ]);
        }else{
            $validator = $this->validateRequest($request, [
                'vehicleHolder',
                'vehicleHolderId',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(
                [
                    'state' => 'error',
                    'errors' => $validator->errors()->all()
                ]);
        }

        // marks completion of on-boarding
        $user = auth()->user();

        $data = [
            'vehicle_header_id' => $request->input('headerId'),
            'businessArea' => $request->input('businessArea'),
            'directorate' => $request->input('directorate'),
            'costCenter' => $request->input('costCenter'),
            'superVisorStaffNumber' => $request->input('responsibleHODId') ?? $request->input('vehicleHolderId'),
            'superVisorName' => $request->input('responsibleHOD') ?? $request->input('vehicleHolder'),
            'isPoolVehicle' => $request->input('isPoolVehicle'),
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
                '*_view' => 'required|file|mimes:jpg,jpeg,png,bmp,tif,tiff'
            ],
            [
                '*.required' => 'Please upload an image',
                '*.mimes' => 'Only =jpg,jpeg,png,bmp,tif,tiff images are allowed',
            ]
        );

        /*if ($validator->fails()) {
            $messages = $validator->messages();
            return Redirect::to('/')->with('message', 'Your erorr message');
        }*/

        return $validator->passes();

    }

}
