<?php

namespace App\Http\Controllers\Requisitions;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Enums\Modules;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\FuelRequisitionPostRequest;
use App\Http\Requests\OdometerValidationRequest;
use App\Models\general\OrganizationalUnits;
use App\Models\RequisitionTypes;
use App\Models\VehicleManagement\ChassisDetail;
use App\Models\VehicleManagement\VehicleHeader;
use App\Services\Requisitions\FuelRequisitionService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FuelRequisitionController extends Controller
{
    private FuelRequisitionService $requisitionService;

    public function __construct(FuelRequisitionService $requisitionService)
    {
        $this->requisitionService = $requisitionService;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $staff_no = auth()->user()->staff_no;
        $requisitions = $this->requisitionService->getMyRequisitions(null);
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
                'requestPayload' => $request->all()
            ]);
        }

        $chassisDetail = ChassisDetail::where('vehicle_header_id', '=', $vehicle->id)->first();

        $valid = $request->get('odometer_reading') > $chassisDetail->initial_odometer_reading;

        return response()->json([
            'success' => $valid,
            'valid' => $valid,
            'message' => $valid ? SystemMessages::valid : SystemMessages::InvalidOdometer,
            'requestPayload' => $request->all()
        ]);
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = Auth::user();

        $organizationalUnit = OrganizationalUnits::where('cc_code', $user->cc_code)
            ->where('bu_code', $user->bu_code)
            ->first();

        $requisitionTypes = RequisitionTypes::where('status', '01')->where('module', Modules::FuelReq)->get();

        $daysToNextRefuel = config('settings.fuel_requisition_validity');

        return view('modules.requisitions.fuel.create')
            ->with(
                compact(
                    'user',
                    'requisitionTypes',
                    'organizationalUnit',
                    'daysToNextRefuel'
                )
            );
    }

    public function store(FuelRequisitionPostRequest $request): JsonResponse
    {
        try {
            return $this->requisitionService->processRequest($request);
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');

            if ($e instanceof FuelRequisitionException || $e instanceof WorkflowTaskCreationFailedException) {
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

        if($requestDetails == null){
            abort(404);
        }

        //$costCenter = CostCenters::where('code_cost_center', $user->cc_code)->first();
        /*$organizationalUnit = OrganizationalUnits::where('code_unit', $requestDetails->cc_code)
            ->first();*/

        $requisitionTypes = RequisitionTypes::where('status', '01')->where('module', 'FR')->get();

        $daysToNextRefuel = config('settings.fuel_requisition_validity');

        $approvalHistory = [];

        return view('modules.requisitions.fuel.show')
            ->with(compact(
                'user',
                'requisitionTypes',
                //'organizationalUnit',
                'requestDetails',
                'daysToNextRefuel',
                'approvalHistory'
            ));
    }

    public function latestRequisition(Request $request): JsonResponse
    {
        if (!$request->has('vehicle_registration')) {
            return response()->json([
                'success' => false,
                'payload' => (object)[],
                'message' => 'Not found'
            ]);
        }

        $payload = $this->requisitionService->getLatestRequisition($request->vehicle_registration);

        return response()->json([
            'success' => !empty($payload),
            'payload' => $payload,
            'message' => !empty($payload) ? 'Found' : 'Not Found'
        ]);
    }
}
