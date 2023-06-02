<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Exceptions\VehicleOnBoardingException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignmentPostRequest;
use App\Http\Requests\BodyDetailsPost;
use App\Http\Requests\ChassisDetailsPostRequest;
use App\Http\Requests\CostingDetailsPost;
use App\Http\Requests\EngineDetailsPost;
use App\Http\Requests\OnboardingVehicleAccessoryRequest;
use App\Http\Requests\VehicleHeaderRequest;
use App\Models\configurations\ConfigAccessories;
use App\Models\configurations\VehicleAccessories;
use App\Models\vehiclemanagement\ChassisDetail;
use App\Models\vehiclemanagement\VehicleHeader;
use App\Services\VehicleManagement\OnBoarding\OnBoardingService;
use App\Services\VehicleManagement\VehicleDetailsService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
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

    public function showDetails(Request $request): View|\Illuminate\Foundation\Application|Factory|Application|RedirectResponse
    {
        if ($request->has('reference')) {
            if (!$request->hasValidSignature()) {
                abort(401);
            }
        }

        if ($request->has('step') && $request->get('step') != "1") {
            if (!$request->hasValidSignature()) {
                abort(401);
            }
        }

        if (!$request->has('step')) {
            return redirect(route('new.vehicle', ['step' => 1]));
        }

        $step = $request->get('step') ?? 0;
        $reference = $request->get('reference') ?? 0;
        $vehicle = null;
        $vehicleDocuments = [];

        if (empty($reference) && $reference != 0) {
            $vehicle = $this->vehicleDetailsService->getVehicleDetails($reference);
            $vehicleDocuments = $this->vehicleDetailsService->getVehicleDocuments($reference);
        }
        $viewName = 'modules.vehicleManagement.details.view';

        return view($viewName)
            ->with(compact('reference', 'vehicle', 'vehicleDocuments'));
    }

    public function resume(Request $request): RedirectResponse
    {
        $reference = $request->get('reference');

        $vehicle = VehicleHeader::where('id', '=', $reference)->first();

        $step = '';
        if ($vehicle->on_boarding_status == StatusHelper::PendingGeneralDataEntry()) {
            $step = 2;
        } elseif ($vehicle->on_boarding_status == StatusHelper::PendingTechnicalDataEntry()) {
            $step = 3;
        } elseif ($vehicle->on_boarding_status == StatusHelper::PendingAccessoriesCheckin()) {
            $step = 4;
        } elseif ($vehicle->on_boarding_status == StatusHelper::PendingCostingDataEntry()) {
            $step = 5;
        } elseif ($vehicle->on_boarding_status == StatusHelper::PendingAssignment()) {
            $step = 6;
        } else if ($vehicle->on_boarding_status = StatusHelper::onboardingComplete()) {
            $step = 7;
        } else {
            $step = 1;
        }

        return redirect(URL::signedRoute('new.vehicle', ['step' => $step, 'reference' => $reference]));
    }


    public function start(Request $request): View|\Illuminate\Foundation\Application|Factory|Application|RedirectResponse
    {
        $vehicle = null;
        $vehicleDocuments = [];

        try {
            if ($request->has('reference')) {
                if (!$request->hasValidSignature()) {
                    abort(401);
                }
            }
            if ($request->has('step') && $request->get('step') != "1") {
                if (!$request->hasValidSignature()) {
                    abort(401);
                }
            }
            if (!$request->has('step')) {
                return redirect(route('new.vehicle', ['step' => 1]));
            }
            $step = $request->get('step') ?? 0;
            $reference = $request->get('reference');

            Log::debug(' Reference after onboarding ' . $reference);
            if (!empty($reference) && $reference != 0) {
                $vehicle = $this->vehicleDetailsService->getVehicleDetails((int)$reference);
                $vehicleDocuments = $this->vehicleDetailsService->getVehicleDocuments((int)$reference);
            }

            $viewName = "modules.vehicleManagement.onboarding.start";

            $accessories = ConfigAccessories::where('status', '=', StatusHelper::active())->get();

            $enteredAccessories = VehicleAccessories::where('vehicle_header_id', '=', (int)$reference)->get();

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
                'message' => SystemMessages::onboardingComplete
            ]);

        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_005');

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

            return response()->json([
                'state' => 'success',
                'request' => $request->all(),
                'payload' => $model,
                'redirectUrl' => URL::signedRoute('new.vehicle', ['step' => 2, 'reference' => $model->id]),
                'message' => 'Your request has bee processed  Successfully, Click ok to proceed with onboarding process'
            ]);

        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_005');
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
                'message' => SystemMessages::generalDataProcessed
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_005');
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
                'redirectUrl' => URL::signedRoute('new.vehicle', ['step' => 4, 'reference' => $model->vehicle_header_id]),
                'message' => 'Vehicle Technical Data Processed Successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_005');
            //'Sorry, some errors were detected while processing your request, please try again later.';
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
                'redirectUrl' => URL::signedRoute('new.vehicle', ['step' => 7, 'reference' => $model->vehicle_header_id]),
                'message' => 'Request Processed Successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_005');
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
            $message = ErrorMessages::getMessage('err_005');
            //'Sorry, some errors were detected while processing your request, please try again later.';
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
                'redirectUrl' => URL::signedRoute('new.vehicle', ['step' => 6, 'reference' => $model->vehicle_header_id]),
                'message' => 'Request Submitted Successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_005');
            //'Sorry, some errors were detected while processing your request, please try again later.';
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

    public function validateVehicleIdentifiers(Request $request): JsonResponse
    {
        $valid = true;

        $message = '';
        switch ($request->get('method')) {
            case 'registration_number':
                $valid = VehicleHeader::where('registration_number', trim($request->get('key')))->count() == 0;
                $message = $valid ? 'Valid' : 'Duplicate Registration Number';
                break;
            case 'chassis':
                $valid = ChassisDetail::where('chassis_number', trim($request->get('key')))->count() == 0;
                $message = $valid ? 'Chassis Number is valid' : 'Duplicate Chassis Number';;
                break;
            case 'engine_number':
                $valid = ChassisDetail::where('engine_number', trim($request->get('key')))->count() == 0;
                $message = $valid ? 'Engine Number valid' : 'Duplicate Engine Number';;
                break;
            case 'motorVehicleCertificate':
                $valid = ChassisDetail::where('white_book_serial', trim($request->get('key')))->count() == 0;
                $message = $valid ? 'White Book Serial is valid' : 'Duplicate White Book Number';;
                break;
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
