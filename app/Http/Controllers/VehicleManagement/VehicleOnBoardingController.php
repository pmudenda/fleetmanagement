<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Exceptions\InvalidDocumentException;
use App\Exceptions\VehicleOnBoardingException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignmentPostRequest;
use App\Http\Requests\BodyDetailsPost;
use App\Http\Requests\CostingDetailsPost;
use App\Http\Requests\EngineDetailsPost;
use App\Http\Requests\VehicleHeaderRequest;
use App\Http\Requests\VehicleManagement\ChassisDetailsPostRequest;
use App\Http\Requests\VehicleManagement\OnboardingVehicleAccessoryRequest;
use App\Models\Settings\Accessory;
use App\Models\VehicleManagement\ChassisDetail;
use App\Models\VehicleManagement\VehicleAccessory;
use App\Models\VehicleManagement\VehicleHeader;
use App\Services\VehicleManagement\OnBoarding\OnBoardingService;
use App\Services\VehicleManagement\VehicleDetailsService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class VehicleOnBoardingController extends Controller
{
    private OnBoardingService $onBoardingService;
    private VehicleDetailsService $vehicleDetailsService;

    public function __construct(OnBoardingService $onBoardingService, VehicleDetailsService $vehicleDetailsService)
    {
        $this->onBoardingService = $onBoardingService;
        $this->vehicleDetailsService = $vehicleDetailsService;
    }

    public function show(): View
    {
        $viewName = 'modules.vehicleManagement.details.index';
        return view($viewName);
    }

    public function showDetails(Request $request): View
    {
        if ($request->has('reference') && !$request->hasValidSignature()) {
            abort(401);
        }

        if ($request->has('step') && $request->get('step') != "1" && !$request->hasValidSignature()) {
            abort(401);
        }

        $step = $request->get('step') ?? 0;
        $reference = $request->get('reference') ?? 0;
        $vehicle = null;
        $vehicleDocuments = [];
        $enteredAccessories = [];
        $accessories = Accessory::where('status', '=', StatusHelper::active())->get();
        if (!empty($reference) && $reference != 0) {

            $vehicle = $this->vehicleDetailsService->getVehicleDetailsById($reference);

            $enteredAccessories = VehicleAccessory::where('vehicle_header_id', '=', (int)$reference)->get();

            $vehicleDocuments = $this->vehicleDetailsService->getVehicleDocuments($reference);
        }

        $viewName = 'modules.vehicleManagement.details.view';

        return view($viewName)
            ->with(compact(
                'step',
                'reference',
                'accessories',
                'enteredAccessories',
                'vehicle',
                'vehicleDocuments'));
    }

    public function resume(Request $request): RedirectResponse
    {
        $reference = $request->get('reference');

        if (empty($reference)) {
            return redirect(route('error'))
                ->with(['message' => 'vehicle Not Found']);
        }

        $vehicle = VehicleHeader::where('id', '=', $reference)->first();

        if (StatusHelper::pendingGeneralDataEntry() == $vehicle->on_boarding_status) {
            $step = 2;
        } elseif ($vehicle->on_boarding_status == StatusHelper::pendingTechnicalDataEntry()) {
            $step = 3;
        } elseif ($vehicle->on_boarding_status == StatusHelper::pendingAccessoriesCheckin()) {
            $step = 4;
        } elseif ($vehicle->on_boarding_status == StatusHelper::pendingCostingDataEntry()) {
            $step = 5;
        } elseif ($vehicle->on_boarding_status == StatusHelper::pendingAssignment()) {
            $step = 6;
        } elseif ($vehicle->on_boarding_status = StatusHelper::onboardingComplete()) {
            $step = 7;
        } else {
            $step = 1;
        }

        return redirect(URL::signedRoute('new.vehicle', ['step' => $step, 'reference' => $reference]));
    }

    public function start(Request $request): View|RedirectResponse
    {
        $vehicle = null;
        $vehicleDocuments = [];
        $enteredAccessories = [];

        try {
            if ($request->has('reference') && !$request->hasValidSignature()) {
                abort(401);
            }
            if ($request->has('step') && $request->get('step') != "1" && !$request->hasValidSignature()) {
                abort(401);
            }

            if (!$request->has('step')) {
                return redirect(route('new.vehicle', ['step' => 1]));
            }
            $step = $request->get('step') ?? 0;
            $reference = $request->get('reference') ?? $request->get('ref');

            Log::debug(' Reference after onboarding ' . $reference);

            if (!empty($reference) && $reference != 0) {
                Log::debug("Find Vehicle By Id $reference");
                $vehicle = $this->vehicleDetailsService->getVehicleDetailsById((int)$reference);

                $enteredAccessories = VehicleAccessory::where('vehicle_header_id', '=', (int)$reference)->get();

                $vehicleDocuments = $this->vehicleDetailsService->getVehicleDocuments((int)$reference);
            }

            $accessories = Accessory::where('status', '=', StatusHelper::active())->get();

            $viewName = "modules.vehicleManagement.onboarding.start";

            return view($viewName)
                ->with(compact(
                    'reference',
                    'vehicle',
                    'step',
                    'enteredAccessories',
                    'accessories',
                    'vehicleDocuments'
                ));
        } catch (Exception $e) {
            Log::error($e);
            $message = "Error on occurred while trying to start Onboarding View";
            return view("error")
                ->with(compact(
                    'message',
                ));
        }
    }

    public function store(AssignmentPostRequest $request): JsonResponse
    {
        try {
            $model = $this->onBoardingService->processAssignmentDetails($request);

            return response()->json([
                'state' => 'success',
                'request' => $request->all(),
                'payload' => $model,
                'redirectUrl' => route('vehicles.list', ['onboarding-complete' => 'yes']),
                'message' => SystemMessages::VEHICLE_ONBOARDED_SUCCESSFULLY
            ]);

        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');

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

    public function storeVehicleHeader(VehicleHeaderRequest $request): JsonResponse
    {
        try {
            $model = $this->onBoardingService->processVehicleHeaderInformation($request);

            return response()->json(
                [
                    'state' => 'success',
                    'request' => $request->all(),
                    'payload' => $model,
                    'redirectUrl' => URL::signedRoute('new.vehicle', [
                        'step' => 2,
                        'reference' => $model->id
                    ]),
                    'message' => SystemMessages::REQUEST_PROCESSED_SUCCESSFULLY
                ]);

        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');
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
            $model = $this->onBoardingService->processChassisDetails($request);
            return response()->json([
                'state' => 'success',
                'request' => $request->all(),
                'payload' => $model,
                'redirectUrl' => URL::signedRoute('new.vehicle', [
                    'step' => 3,
                    'reference' => $model->vehicle_header_id
                ]),
                'message' => SystemMessages::VEHICLE_GENERAL_DATA_PROCESSED_SUCCESSFULLY
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');
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

    public function storeEngineDetails(EngineDetailsPost $request): JsonResponse
    {
        try {
            $model = $this->onBoardingService->processEngineDetails($request);
            return response()->json([
                'state' => 'success',
                'request' => $request->all(),
                'payload' => $model,
                'redirectUrl' => URL::signedRoute('new.vehicle', [
                    'step' => 4,
                    'reference' => $model->vehicle_header_id
                ]),
                'message' => SystemMessages::TECHNICAL_DATA_SAVED
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');
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

    public function storeCostingDetails(CostingDetailsPost $request): JsonResponse
    {
        try {
            $model = $this->onBoardingService->processCostingDetails($request);
            return response()->json([
                'state' => 'success',
                'request' => $request->all(),
                'payload' => $model,
                'redirectUrl' => URL::signedRoute('new.vehicle', [
                    'step' => 7,
                    'reference' => $model->vehicle_header_id
                ]),
                'message' => 'Request Processed Successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');
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

    public function storeAccessoryDetails(OnboardingVehicleAccessoryRequest $request): JsonResponse
    {
        try {
            $model = $this->onBoardingService->processAccessory($request);
            return response()->json([
                'state' => 'success',
                'request' => $request->all(),
                'payload' => $model,
                'redirectUrl' => URL::signedRoute('new.vehicle', [
                    'step' => 5,
                    //'reference' => $model->vehicle_header_id
                    'reference' => $request->get('headerId')
                ]),
                'message' => 'Request Processed Successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');
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

    public function storeBodyDetails(BodyDetailsPost $request): JsonResponse
    {
        try {
            $model = $this->onBoardingService->processingBodyDetails($request);
            return response()->json([
                'state' => 'success',
                'request' => $request->all(),
                'payload' => $model,
                'redirectUrl' => URL::signedRoute('new.vehicle', [
                    'step' => 6,
                    'reference' => $model->vehicle_header_id
                ]),
                'message' => 'Request Submitted Successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');
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
     * @throws Exception
     */
    public function validateVehicleIdentifiers(Request $request): JsonResponse
    {
        $valid = true;

        $message = '';
        $documentIdentity = trim($request->get('key'));
        switch ($request->get('method')) {
            case 'registration_number':
                $valid = VehicleHeader::where('registration_number', $documentIdentity)
                        ->count() == 0;
                $message = $valid ? 'Valid' : 'Duplicate Registration Number';
                break;
            case 'chassis':
                $valid = ChassisDetail::where('chassis_number', $documentIdentity)
                        ->count() == 0;
                $message = $valid ? 'Chassis Number is valid' : 'Duplicate Chassis Number';
                break;
            case 'engine_number':
                $valid = ChassisDetail::where('engine_number', $documentIdentity)
                        ->count() == 0;
                $message = $valid ? 'Engine Number valid' : 'Duplicate Engine Number';
                break;
            case 'motorVehicleCertificate':
                $valid = ChassisDetail::where('white_book_serial', $documentIdentity)
                        ->count() == 0;
                $message = $valid ? 'White Book Serial is valid' : 'Duplicate White Book Number';
                break;
            default:
                throw new InvalidDocumentException('Unexpected value');
        }

        return response()->json([
            'state' => 'success',
            'payload' => [
                'validity' => $valid,
                'message' => $message
            ],
            'request' => $request->all()
        ]);
    }
}
