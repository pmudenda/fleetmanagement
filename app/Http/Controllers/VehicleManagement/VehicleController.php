<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Enums\Modules;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\Settings\Accessory;
use App\Models\Settings\general\Status;
use App\Models\Settings\WorkShop;
use App\Models\VehicleManagement\VehicleAccessory;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Services\Integration\ProcurementSystemIntegrationService;
use App\Services\VehicleManagement\VehicleDetailsService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

            Log::info('reference is ' . $ref);
            if ($ref == 0) {
                return redirect(
                    route('vehicles.list')
                )->with(['error' => 'Missing Required Parameters']);
            }

            Log::info('Fetching Vehicle Details ' . $ref);

            $vehicle = $this->vehicleDetailsService->getVehicleDetails($ref);

            if (!empty(!$vehicle)) {
                Log::info('Vehicle Details Found ');
            } else {
                Log::info('Vehicle Details Not Found ');
            }

            $vehicleDocuments = $this->vehicleDetailsService->getVehicleDocuments($ref);

            $enteredAccessories = VehicleAccessory::where('vehicle_header_id', '=', (int)$ref)->get();

            $fuel_cost_by_year = DB::table('zfm_fuel_cost')
                ->where('reg_no', '=', $vehicle->registration_number)
                ->select(DB::raw('SUM(ttl) as cost,year'))
                ->groupBy('year')
                ->orderBy('year')
                ->get();

            $spares_cost_by_year = DB::table('zfm_spare_cost')
                ->where('reg_no', '=', $vehicle->registration_number)
                ->select(DB::raw('SUM(value_amount) as cost, EXTRACT(YEAR FROM TO_DATE(document_date)) year'))
                ->groupBy(DB::raw('EXTRACT(YEAR FROM TO_DATE(document_date))'))
                ->orderBy(DB::raw('EXTRACT(YEAR FROM TO_DATE(document_date))'))
                ->get();

            return response()->json([
                'payload' => [
                    'vehicle' => $vehicle,
                    'documents' => $vehicleDocuments,
                    'enteredAccessories' => $enteredAccessories,
                    'cost_by_year' => $fuel_cost_by_year,
                    'spares_cost_by_year' => $spares_cost_by_year
                ],
                'success' => !empty($vehicle),
                'message' => !empty($vehicle)
                    ? 'Vehicle Details retrieved successfully'
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

    public function getVehicleDetailsByRegistration(Request $request): JsonResponse
    {
        try {
            if (empty($request->vehicle_registration)) {
                return response()->json([
                    'success' => false,
                    'statusDescription' => 'Bad Request',
                    'message' => 'Missing required parameter'
                ]);
            }

            // determine material type in form of fuel
            $vehicle = $this->vehicleDetailsService->getBasicVehicleDetails($request->vehicle_registration);

            if (empty($vehicle)) {
                return response()->json([
                    'success' => false,
                    'statusDescription' => 'Not Found',
                    'message' => 'Vehicle not found'
                ]);
            }

            $vehicleImages = $this->vehicleDetailsService->getVehicleImages($vehicle->vehicle_header_id);

            $accessories = $this->vehicleDetailsService->getSubmittedAccessories($vehicle->vehicle_header_id);

            $article = $this->procurementService->getArticleByCode($vehicle->fuel_types);


            $vehicle_state = '';
            $vehicle_tom_card_message = '';

            if ($vehicle->on_boarding_status != StatusHelper::onboardingComplete()) {
                $vehicle_state = str_replace("@reg",
                    $vehicle->registration_number, SystemMessages::vehiclePendingOnboardingCompletion());
            } elseif ($vehicle->status == StatusHelper::vehicleInWorkshop()) {
                $jobCard = JobCardHeader::where('reg_no', $vehicle->registration_number)->first();

                $workshopName = "";
                if (!empty($jobCard) && !empty($jobCard->workshop_code)) {
                    WorkShop::where('workshop_code', $jobCard->workshop_code)->first()->workshop_name;
                }

                $vehicle_state = str_replace("@reg",
                    $vehicle->registration_number,
                    str_replace("@workshop",
                        $workshopName,
                        SystemMessages::vehicleInWorkshop())
                );
            }

            if ($vehicle->has_tom_card === 'Y') {
                $vehicle_tom_card_message = str_replace("@reg",
                    $vehicle->registration_number, ErrorMessages::getMessage('err_0023'));
            }

            return response()->json([
                'payload' => [
                    'vehicle' => $vehicle,
                    'article' => $article,
                    'images' => $vehicleImages,
                    'accessories' => $accessories,
                    'vehicle_state' => $vehicle_state,
                    'vehicle_tom_card_message' => $vehicle_tom_card_message
                ],
                'success' => !empty($vehicle),
                'message' => 'Vehicle Details retrieved successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    }

    public function getVehicleDetails(Request $request): JsonResponse
    {
        try {

            if (empty($request->vehicle_registration)) {
                return response()->json([
                    'success' => false,
                    'statusDescription' => 'Bad Request',
                    'message' => 'Missing required parameter'
                ]);
            }

            // determine material type in form of fuel
            $vehicle = $this->vehicleDetailsService->getBasicVehicleDetails($request->vehicle_registration);

            if (empty($vehicle)) {
                return response()->json([
                    'success' => false,
                    'statusDescription' => 'Not Found',
                    'message' => 'Vehicle not found'
                ]);
            }

            $vehicle_state = '';

            if ($vehicle->on_boarding_status != StatusHelper::onboardingComplete()) {
                $vehicle_state = str_replace("@reg",
                    $vehicle->registration_number, SystemMessages::vehiclePendingOnboardingCompletion());
            } elseif ($vehicle->status == StatusHelper::vehicleInWorkshop()) {
                $workshopCode = JobCardHeader::where('reg_no', $vehicle->registration_number)->first()->workshop_code;
                $workshopName = WorkShop::where('workshop_code', $workshopCode)->first()->workshop_name;
                $vehicle_state = str_replace("@reg",
                    $vehicle->registration_number,
                    str_replace("@workshop",
                        $workshopName,
                        SystemMessages::vehicleInWorkshop())
                );
            }

            return response()->json([
                'payload' => [
                    'vehicle' => $vehicle,
                    'vehicle_state' => $vehicle_state
                ],
                'success' => !empty($vehicle),
                'message' => 'Vehicle Details retrieved successfully'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => ErrorMessages::getMessage('err_0005')
            ]);
        }
    }

    public function list(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        Log::info('Has Get Records'. $request->has('getRecords'));
        if ($request->has('getRecords')) {
            Log::debug("Get Records Present");
            $vehicleList = $this->vehicleDetailsService->getFilteredVehiclesInformation($request);
        } else {
            $vehicleList = $this->vehicleDetailsService->getAllVehicles();
        }

        $statusList = Status::where('module', '=', Modules::VEHICLE->value)->get();
        return view('modules.vehicleManagement.vehicleList')
            ->with(compact('vehicleList', 'statusList'));
    }

    public function record(Request $request): JsonResponse
    {
        $this->vehicleDetailsService->getAllVehicles();
        $totalRecords = 0;
        return response()->json([
            "draw" => $request->draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => []
        ]);
    }

    public function register(Request $request): View
    {
        return view('modules.vehicleManagement.vehicleList');
    }

    public function accessories(Request $request): View
    {
        $accessories = Accessory::where('status', '=', StatusHelper::active())
            ->get();

        return view('modules.vehicleManagement.general.accessories')
            ->with(compact('accessories'));
    }
}
