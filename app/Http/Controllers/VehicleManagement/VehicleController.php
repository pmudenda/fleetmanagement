<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\configurations\Accessory;
use App\Models\VehicleManagement\VehicleHeader;
use App\Services\Integration\ProcurementSystemIntegrationService;
use App\Services\VehicleManagement\VehicleDetailsService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller
{
    private VehicleDetailsService $vehicleDetailsService;
    private ProcurementSystemIntegrationService $procurementService;

    public function __construct(VehicleDetailsService               $vehicleDetailsService,
                                ProcurementSystemIntegrationService $procurementService)
    {
        $this->vehicleDetailsService = $vehicleDetailsService;
        $this->procurementService = $procurementService;
    }

    public function getAllDetails(Request $request): JsonResponse
    {
        try {
            if (empty($request->has('reference'))) {
                return response()->json([
                    'success' => 'false',
                    'statusDescription' => 'Bad Request',
                    'message' => 'Missing required parameter'
                ]);
            }

            $ref = $request->get('reference');

            $vehicle = null;
            $vehicleDocuments = null;

            Log::info('reference is ' . $ref);
            if ($ref != 0) {
                $vehicle = $this->vehicleDetailsService->getVehicleDetails($ref);
                $vehicleDocuments = $this->vehicleDetailsService->getVehicleDocuments($ref);
            }

            return response()->json([
                'payload' => [
                    'vehicle' => $vehicle,
                    'documents' => $vehicleDocuments
                ],
                'success' => !empty($vehicle),
                'message' => !empty($vehicle) ? 'Vehicle Details retrieved successfully'
                    : 'Could not read vehicle details'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => 'false',
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    }

    public function getDetails(Request $request): JsonResponse
    {
        try {
            if (empty($request->vehicle_registration)) {
                return response()->json([
                    'success' => 'false',
                    'statusDescription' => 'Bad Request',
                    'message' => 'Missing required parameter'
                ]);
            }

            // determine material type in form of fuel
            $vehicle = $this->vehicleDetailsService->getBasicVehicleDetails($request->vehicle_registration);

            if (!$vehicle) {
                return response()->json([
                    'success' => 'false',
                    'statusDescription' => 'Not Found',
                    'message' => 'Vehicle not found'
                ]);
            }
            $vehicleImages = $this->vehicleDetailsService->getVehicleImages($vehicle->vehicle_header_id);
            $accessories = $this->vehicleDetailsService->getSubmittedAccessories($vehicle->vehicle_header_id);
            $article = $this->procurementService->getArticleByCode($vehicle->fuel_types);
            $vehicle_state = '';

            if ($vehicle->on_boarding_status != StatusHelper::onboardingComplete()) {
                $vehicle_state = str_replace("@reg",
                    $vehicle->registration_number, SystemMessages::vehiclePendingOnboardingCompletion());
            } elseif ($vehicle->status == StatusHelper::vehicleInWorkshop()) {
                $vehicle_state = str_replace("@reg",
                    $vehicle->registration_number,
                    SystemMessages::vehicleInWorkshop());
            }

            return response()->json([
                'payload' => [
                    'vehicle' => $vehicle,
                    'article' => $article,
                    'images' => $vehicleImages,
                    'accessories' => $accessories,
                    'vehicle_state' => $vehicle_state
                ],
                'success' => !empty($vehicle),
                'message' => 'Vehicle Details retrieved successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => 'false',
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    }

    public function list(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $vehicleList = VehicleHeader::get();
        return view('modules.vehicleManagement.vehicleList')
            ->with(compact('vehicleList'));
    }

    public function accessories(Request $request): View
    {
        $accessories = Accessory::where('status', '=', StatusHelper::active())
            ->get();

        return view('modules.vehicleManagement.general.accessories')
            ->with(compact('accessories'));
    }

    public function register(Request $request): View
    {
        return view('modules.vehicleManagement.vehicleList');
    }
}
