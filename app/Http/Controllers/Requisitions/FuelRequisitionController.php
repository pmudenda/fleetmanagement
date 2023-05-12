<?php

namespace App\Http\Controllers\Requisitions;

use App\Constants\SystemMessages;
use App\Exceptions\VehicleOnBoardingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FuelRequisitionPostRequest;
use App\Http\Requests\OdometerValidationRequest;
use App\Models\general\CostCenters;
use App\Models\MaterialHeader;
use App\Models\RequisitionTypes;
use App\Models\vehiclemanagement\ChassisDetail;
use App\Models\vehiclemanagement\VehicleHeader;
use App\Services\Requisitions\FuelRequisitionService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FuelRequisitionController extends Controller
{
    private FuelRequisitionService $requisitionService;

    public function __construct(FuelRequisitionService $requisitionService)
    {
        $this->requisitionService = $requisitionService;
    }


    public function __call(string $name, array $arguments)
    {
        // TODO: Implement __call() method.
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $requisitions = MaterialHeader::get();
        return view("modules.requisitions.fuel.list")
            ->with(compact('requisitions'));
    }

    public function validateOdometer(OdometerValidationRequest $request): JsonResponse
    {
        $vehicle = VehicleHeader::where('registration_number', trim($request->get('vehicle_registration')))->first();

        if (empty($vehicle)) {
            return response()->json([
                'success' => false,
                'message' => "Vehicle not found",
                'requestPayload' => all()
            ]);
        }

        $chassisDetail = ChassisDetail::where('vehicle_header_id', '=', $vehicle->id)->first();

        //trim($request->get('odometer_reading'))

        $valid = $chassisDetail->initial_odometer_reading < $request->get('odometer_reading');

        return response()->json([
            'success' => false,
            'valid' => $valid,
            'message' => SystemMessages::valid,
            'requestPayload' => $request->all()
        ]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = Auth::user();
        $costCenter = CostCenters::where('code_cost_center', $user->cc_code)->first();
        $requisitionTypes = RequisitionTypes::where('status', '01')->where('module', 'FR')->get();
        $daysToNextRefuel = config('settings.fuel_requisition_validity');

        return view('modules.requisitions.fuel.create')
            ->with(compact('user', 'requisitionTypes', 'costCenter', 'daysToNextRefuel'));
    }

    public function store(FuelRequisitionPostRequest $request): JsonResponse
    {
        try {
            return $this->requisitionService->processRequest($request);
        } catch (\Exception $e) {
            Log::error($e);
            $message = 'We could not complete processing your request due to an error';

            if ($e instanceof VehicleOnBoardingException) {
                $message = $e->getMessage();
            }
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }

    public function show(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $req_no = $request->get('ref');

        $user = Auth::user();

        $requestDetails = $this->requisitionService->getRequisitionDetail($req_no);

        $costCenter = CostCenters::where('code_cost_center', $user->cc_code)->first();

        $requisitionTypes = RequisitionTypes::where('status', '01')->where('module', 'FR')->get();

        $daysToNextRefuel = config('settings.fuel_requisition_validity');

        $approvalHistory = [];

        return view('modules.requisitions.fuel.show')
            ->with(compact(
                'user',
                'requisitionTypes',
                'costCenter',
                'requestDetails',
                'daysToNextRefuel',
                'approvalHistory'
            ));
    }
}
