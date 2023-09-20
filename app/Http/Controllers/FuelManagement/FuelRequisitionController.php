<?php

namespace App\Http\Controllers\FuelManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Enums\Modules;
use App\Exceptions\DataNotFoundException;
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
use App\Models\Town;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Services\Requisitions\DistanceChartService;
use App\Services\Requisitions\FuelRequisitionService;
use App\Services\VehicleManagement\OdometerValidationService;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mockery\CountValidator\Exception;

class FuelRequisitionController extends Controller
{
    private readonly OdometerValidationService $odometerValidationService;
    private FuelRequisitionService $requisitionService;
    private DistanceChartService $distanceChartService;

    public function __construct(FuelRequisitionService    $requisitionService,
                                OdometerValidationService $odometerValidationService,
                                DistanceChartService      $distanceChartService)
    {
        $this->odometerValidationService = $odometerValidationService;
        $this->requisitionService = $requisitionService;
        $this->distanceChartService = $distanceChartService;
    }

    public function list(): View
    {
        $requisitions = $this->requisitionService->getMyRequisitions(null);
        $requisitionType = "FUEL";
        return view("modules.fuelManagement.requisitions.list")
            ->with(compact(
                    'requisitions',
                    'requisitionType'
                )
            );
    }

    public function show(Request $request): View
    {
        list($user,
            $requestDetails,
            $supportingDocument,
            $workflowTask,
            $requisitionTypes,
            $daysToNextRefuel,
            $approvalHistory
            ) = $this->getRequisitionDetails($request);

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

    public function validateOdometer(OdometerValidationRequest $request): JsonResponse
    {
        try {
            $vehicleRegistration = trim($request->get('vehicle_registration'));
            $userProvidedOdometer = $request->get('odometer_reading');

            $valid = $this->odometerValidationService->validate(
                $vehicleRegistration,
                $userProvidedOdometer);

            return response()->json(
                FleetMasterJsonResponse::response(
                    '',
                    $valid,
                    $valid ?
                        SystemMessages::ODOMETER_VALIDATED_SUCCESSFULLY
                        : ErrorMessages::getMessage("err_0018")

                )
            );
        } catch (\Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage("err_0005");

            if ($e instanceof DataNotFoundException) {
                $message = $e->getMessage();
            }

            return response()->json(
                FleetMasterJsonResponse::response(
                    '',
                    false,
                    $message
                )
            );
        }
    }

    public function create(Request $request): View|Application
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

        $cities = $this->distanceChartService->getInterCityDistanceArray();
        $citiesFrom = Town::orderBy('town_name')->get();

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

    public function store(FuelRequisitionPostRequest $request): JsonResponse
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

    public function resubmit(FuelRequisitionUpdate $request): JsonResponse
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

    public function edit(Request $request): string
    {
        Log::info("Running Fuel Edit Request");

        list($user,
            $requestDetails,
            $supportingDocument,
            $workflowTask,
            $requisitionTypes,
            $daysToNextRefuel,
            $approvalHistory) = $this->getRequisitionDetails($request);

        $cities = $this->distanceChartService->getInterCityDistanceArray();
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
            ));
    }

    public function findLatestRequisition(Request $request): JsonResponse
    {
        if (!$request->has('vehicle_registration')) {
            return response()->json([
                'success' => false,
                'payload' => (object)[],
                'message' => 'Not found'
            ]);
        }

        $payload = $this->requisitionService->getLatestRequisition(
            $request->vehicle_registration
        );

        return response()->json(
            FleetMasterJsonResponse::response(
                !empty($payload) ? 'success' : 'failure',
                !empty($payload),
                !empty($payload) ? 'Found' : 'Not Found',
                $payload
            )
        );
    }

    public function getDistance(Request $request): JsonResponse
    {
        try {
            $result = $this->distanceChartService->getDistance(
                $request->input('departure'),
                $request->input('destination')
            );
            return response()->json(
                FleetMasterJsonResponse::response(
                    '',
                    true,
                    null,
                    $result
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(
                FleetMasterJsonResponse::response(
                    '',
                    false,
                    $e->getMessage()
                )
            );
        }

    }

    /**
     * @param Request $request
     * @return void
     */
    private function validateSignature(Request $request): void
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getRequisitionDetails(Request $request): array
    {
        $this->validateSignature($request);

        $requisitionNumber = $request->get('ref');
        $user = Auth::user();

        $requestDetails = $this->requisitionService->getRequisitionDetail(
            $requisitionNumber
        );

        $supportingDocument = File::where('reference_number',
            '=',
            $requisitionNumber
        )->first();

        if ($requestDetails == null) {
            abort(404);
        }

        $workflowTask = WorkflowTaskHeader::where('reference', '=', $requisitionNumber)->first();

        $requisitionTypes = RequisitionType::where('status', '01')->where('module', 'FR')->get();

        $daysToNextRefuel = config('settings.fuel_requisition_validity');

        $approvalHistory = [];
        return array(
            $user,
            $requestDetails,
            $supportingDocument,
            $workflowTask,
            $requisitionTypes,
            $daysToNextRefuel,
            $approvalHistory
        );
    }
}
