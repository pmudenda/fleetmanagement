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
use App\Http\Requests\FuelRequisitionUpdate;
use App\Http\Requests\VehicleManagement\OdometerValidationRequest;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Common\File;
use App\Models\Common\OrganizationalUnit;
use App\Models\RequisitionType;
use App\Models\VehicleManagement\VehicleHeader;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Services\Requisitions\FuelRequisitionService;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mockery\CountValidator\Exception;

class FuelRequisitionController extends Controller
{
    private FuelRequisitionService $requisitionService;

    public function __construct(FuelRequisitionService   $requisitionService)
    {
        $this->requisitionService = $requisitionService;
    }

    public function index(): View
    {
        $requisitions = [] ;// $this->requisitionService->getMyRequisitions(null);
        $requisitionType = "FUEL";
        return view("modules.fuelManagement.requisitions.list")
            ->with(compact('requisitions', 'requisitionType'));
    }


    public function validateOdometer(OdometerValidationRequest $request): JsonResponse
    {
        try {
            $vehicle = VehicleHeader::where('registration_number',
                '=',
                trim($request->get('vehicle_registration'))
            )->first();

            if (empty($vehicle)) {
                return response()->json([
                    'success' => false,
                    'message' => "Vehicle not found"
                ]);
            }

            $userProvidedOdometer = $request->get('odometer_reading');

            Log::debug("Validating Odometer Usr $userProvidedOdometer against Veh $vehicle->mileage");

            $valid = (int)$userProvidedOdometer >= $vehicle->mileage;
            return response()->json([
                'success' => $valid,
                'valid' => $valid,
                'message' => $valid ?
                    SystemMessages::ODOMETER_VALIDATED_SUCCESSFULLY
                    : ErrorMessages::getMessage("err_0018"),
                'requestPayload' => $request->all()
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    'success' => false,
                    'valid' => null,
                    'message' => ErrorMessages::getMessage("err_0005")
                ]
            );
        }
    }

    public function create(): View|Application
    {
        $user = Auth::user();

        $organizationalUnit = OrganizationalUnit::where('cc_code', $user->cc_code)
            ->where('bu_code', $user->bu_code)
            ->first();

        $requisitionTypes = RequisitionType::where('status', StatusHelper::active())
            ->where('module', Modules::FUEL_REQUISITION->value)
            ->orderBy('code')
            ->get();

        $daysToNextRefuel = config('settings.fuel_requisition_validity');

        $cities = [];// $this->interCityDistanceService->getInterCityDistanceArray();
        $citiesFrom = null;//Town::orderBy('town_name')->get();

        return view('modules.fuelManagement.requisitions.create')
            ->with(
                compact(
                    'user',
                    'requisitionTypes',
                    'organizationalUnit',
                    'daysToNextRefuel',
                    'cities',
                    'citiesFrom'
                )
            );
    }

    public function submitRequisition(FuelRequisitionPostRequest $request): JsonResponse
    {
        try {
            return $this->requisitionService->processRequest($request);
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');

            if ($e instanceof FuelRequisitionException
                || $e instanceof WorkflowTaskCreationFailedException) {
                $message = $e->getMessage();
            }
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }

    public function update(FuelRequisitionUpdate $request): JsonResponse
    {
        try {
            return $this->requisitionService->processRequisitionUpdate($request);
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');

            if ($e instanceof FuelRequisitionException
                || $e instanceof WorkflowTaskCreationFailedException) {
                $message = $e->getMessage();
            }
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }

    public function show(Request $request): View
    {
        $this->validateSignature($request);

        $requisitionNumber = $request->get('ref');
        $user = Auth::user();

        $requestDetails = $this->requisitionService->getRequisitionDetail($requisitionNumber);

        $supportingDocument = File::where('reference_number', '=', $requisitionNumber)
            ->first();

        if ($requestDetails == null) {
            abort(404);
        }


        $workflowTask = WorkflowTaskHeader::where('reference', '=', $requisitionNumber)->first();

        $requisitionTypes = RequisitionType::where('status', '01')->where('module', 'FR')->get();

        $daysToNextRefuel = config('settings.fuel_requisition_validity');

        $approvalHistory = [];

        return view('modules.fuelManagement.requisitions.show')
            ->with(compact(
                'user',
                'requisitionTypes',
                'requestDetails',
                'daysToNextRefuel',
                'approvalHistory',
                'workflowTask',
                'supportingDocument'
            ));
    }

    public function editFuelRequisition(Request $request): string
    {
        Log::info("Running Fuel Edit Request");

        //$this->validateSignature($request);
       /* $requisitionNumber = $request->get('ref');
        $user = Auth::user();

        $requestDetails = null; //$this->requisitionService->getRequisitionDetail($requisitionNumber);

        $supportingDocument = File::where('reference_number', '=', $requisitionNumber)
            ->first();

        if ($requestDetails == null) {
            //abort(404);
        }


        $workflowTask = WorkflowTaskHeader::where('reference', '=', $requisitionNumber)->first();

        $requisitionTypes = RequisitionType::where('status', '01')->where('module', 'FR')->get();

        $daysToNextRefuel = config('settings.fuel_requisition_validity');

        $approvalHistory = [];


        $cities = $this->interCityDistanceService->getInterCityDistanceArray();
        $citiesFrom = Town::orderBy('town_name')->get();


        return view('modules.fuelManagement.requisitions.edit')
            ->with(compact(
                'user',
                'requisitionTypes',
                'requestDetails',
                'daysToNextRefuel',
                'approvalHistory',
                'workflowTask',
                'supportingDocument',
                'cities',
                'citiesFrom'
            ));*/

        return "Page Here";
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

        return response()->json(
            FleetMasterJsonResponse::response(
                !empty($payload) ? 'success' : 'failure',
                !empty($payload),
                !empty($payload) ? 'Found' : 'Not Found',
                $payload
            )
        );
    }

    public function getDistanceBetween($fromCity, $toCity): int
    {
        return 0; //return $this->interCityDistanceService->getDistance($fromCity, $toCity);
    }

    public function getDistance(Request $request): JsonResponse
    {
        try {
            $result = $this->getDistanceBetween(
                $request->input('departure'),
                $request->input('destination')
            );
            return response()->json(array(
                'success' => true,
                'data' => $result
            ));
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(array(
                'success' => false,
                'data' => $e->getMessage()
            ));
        }

    }

    /**
     * @param Request $request
     * @return void
     */
    public function validateSignature(Request $request): void
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
    }
}
