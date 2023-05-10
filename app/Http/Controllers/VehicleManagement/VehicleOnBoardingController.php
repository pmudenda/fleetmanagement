<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Exceptions\VehicleOnBoardingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignmentPostRequest;
use App\Http\Requests\BodyDetailsPost;
use App\Http\Requests\ChassisDetailsPostRequest;
use App\Http\Requests\CostingDetailsPost;
use App\Http\Requests\EngineDetailsPost;
use App\Http\Requests\VehicleHeaderRequest;
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
use Illuminate\Support\Facades\Validator;

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
        if ($reference != 0) {
            $vehicle = $this->vehicleDetailsService->getVehicleDetails($reference);
            $vehicleDocuments = $this->vehicleDetailsService->getVehicleDocuments($reference);
        }
        $viewName = 'vehicleManagement.details.view';

        /*match ($step) {
            '1' => "",
            '2' => "vehicleManagement.onboarding.step2",
            '3' => "vehicleManagement.onboarding.step3",
            '4' => "vehicleManagement.onboarding.step4",
            '5' => "vehicleManagement.onboarding.step5",
            '6' => "vehicleManagement.onboarding.step6",
            default => "vehicleManagement.onboarding.index",
        };*/


        return view($viewName)
            ->with(compact('reference', 'vehicle', 'vehicleDocuments'));
    }


    public function start(Request $request): View|\Illuminate\Foundation\Application|Factory|Application|RedirectResponse
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
        if ($reference != 0) {
            $vehicle = $this->vehicleDetailsService->getVehicleDetails($reference);
            $vehicleDocuments = $this->vehicleDetailsService->getVehicleDocuments($reference);
        }
        $viewName = match ($step) {
            '1' => "vehicleManagement.onboarding.step1",
            '2' => "vehicleManagement.onboarding.step2",
            '3' => "vehicleManagement.onboarding.step3",
            '4' => "vehicleManagement.onboarding.step4",
            '5' => "vehicleManagement.onboarding.step5",
            '6' => "vehicleManagement.onboarding.step6",
            default => "vehicleManagement.onboarding.index",
        };


        return view($viewName)
            ->with(compact('reference', 'vehicle', 'vehicleDocuments'));
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

    public function storeVehicleHeader(VehicleHeaderRequest $request): JsonResponse
    {
        try {
            $model = $this->onBoardingService->processVehicleHeaderInformation($request);

            return response()->json([
                'state' => 'success',
                'request' => $request->all(),
                'payload' => $model,
                'redirectUrl' => URL::signedRoute('new.vehicle', ['step' => 2, 'reference' => $model->id]),
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
            $model = $this->onBoardingService->processChassisDetails($request);
            return response()->json([
                'state' => 'success',
                'request' => $request->all(),
                'payload' => $model,
                'redirectUrl' => URL::signedRoute('new.vehicle', ['step' => 3, 'reference' => $model->vehicle_header_id]),
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

    public function storeEngineDetails(EngineDetailsPost $request): JsonResponse
    {
        try {
            $model = $this->onBoardingService->processEngineDetails($request);
            return response()->json([
                'state' => 'success',
                'request' => $request->all(),
                'payload' => $model,
                'redirectUrl' => URL::signedRoute('new.vehicle', ['step' => 4, 'reference' => $model->vehicle_header_id]),
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

    public function storeCostingDetails(CostingDetailsPost $request): JsonResponse
    {
        try {
            $model = $this->onBoardingService->processCostingDetails($request);
            return response()->json([
                'state' => 'success',
                'request' => $request->all(),
                'payload' => $model,
                'redirectUrl' => URL::signedRoute('new.vehicle', ['step' => 5, 'reference' => $model->vehicle_header_id]),
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
