<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Constants\ErrorMessages;
use App\Exceptions\VehicleOnBoardingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignmentPostRequest;
use App\Http\Requests\BodyDetailsPost;
use App\Http\Requests\ChassisDetailsPostRequest;
use App\Http\Requests\CostingDetailsPost;
use App\Http\Requests\EngineDetailsPost;
use App\Http\Requests\VehicleHeaderRequest;
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
        $viewName = 'vehicleManagement.details.index';
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
        $viewName = 'vehicleManagement.details.view';

        return view($viewName)
            ->with(compact('reference', 'vehicle', 'vehicleDocuments'));
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
            $viewName = match ($step) {
                '1' => "vehicleManagement.onboarding.step6",
                '2' => "vehicleManagement.onboarding.step6",
                '3' => "vehicleManagement.onboarding.step6",
                '4' => "vehicleManagement.onboarding.step6",
                '5' => "vehicleManagement.onboarding.step6",
                '6' => "vehicleManagement.onboarding.step6",
                '7' => "vehicleManagement.onboarding.step6",
                default => "vehicleManagement.onboarding.index",
            };
            return view($viewName)
                ->with(compact(
                    'reference',
                    'vehicle',
                    'step',
                    'vehicleDocuments'
                ));
        } catch (Exception $e) {
            Log::error($e);

            return view("vehicleManagement.onboarding.step1")
                ->with(compact(
                    'reference',
                    'vehicle',
                    'vehicleDocuments'
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
                'message' => 'Vehicle Onboarded Successfully. You will now be redirected to vehicle Register'
            ]);

        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::internalServerError;
            //'Sorry, some errors were detected while processing your vehicle onboarding request, please try again later.';
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
            $message = ErrorMessages::internalServerError;
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
                'redirectUrl' => URL::signedRoute('new.vehicle', ['step' => 3, 'reference' => $model->vehicle_header_id]),
                'message' => 'Vehicle General Data Processed Successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::internalServerError;
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
            $message = ErrorMessages::internalServerError;
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
                'redirectUrl' => URL::signedRoute('new.vehicle', ['step' => 5, 'reference' => $model->vehicle_header_id]),
                'message' => 'Request Processed Successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::internalServerError;
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

    public function storeAccessoryDetails(CostingDetailsPost $request): JsonResponse
    {
        try {
            $model = $this->onBoardingService->processCostingDetails($request);
            return response()->json([
                'state' => 'success',
                'request' => $request->all(),
                'payload' => $model,
                'redirectUrl' => URL::signedRoute('new.vehicle', ['step' => 5, 'reference' => $model->vehicle_header_id]),
                'message' => 'Request Processed Successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::internalServerError;
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
            $message = ErrorMessages::internalServerError;
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
