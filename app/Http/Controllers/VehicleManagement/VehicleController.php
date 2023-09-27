<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Enums\DocumentState;
use App\Enums\Modules;
use App\Exceptions\DataNotFoundException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Settings\Accessory;
use App\Models\Settings\general\Status;
use App\Models\Settings\WorkShop;
use App\Models\VehicleManagement\VehicleAccessory;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Services\Integration\ProcurementSystemIntegrationService;
use App\Services\VehicleManagement\FitnessService;
use App\Services\VehicleManagement\InsuranceService;
use App\Services\VehicleManagement\RoadTaxService;
use App\Services\VehicleManagement\VehicleDetailsService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class VehicleController extends Controller
{
    public const VEHICLE_DETAILS_RETRIEVED_SUCCESSFULLY = 'Vehicle Details retrieved successfully';
    const REG = "@reg";
    private VehicleDetailsService $vehicleDetailsService;
    private ProcurementSystemIntegrationService $procurementService;
    private InsuranceService $insuranceService;
    private RoadTaxService $roadTaxService;
    private FitnessService $vehicleFitnessService;

    public function __construct(
        VehicleDetailsService               $vehicleDetailsService,
        ProcurementSystemIntegrationService $procurementService,
        InsuranceService                    $insuranceService,
        RoadTaxService                      $roadTaxService,
        FitnessService                      $vehicleFitnessService
    )
    {
        $this->vehicleDetailsService = $vehicleDetailsService;
        $this->procurementService = $procurementService;
        $this->insuranceService = $insuranceService;
        $this->roadTaxService = $roadTaxService;
        $this->vehicleFitnessService = $vehicleFitnessService;
    }

    public function getVehicleOverViewDetails(Request $request): JsonResponse
    {
        try {
            $ref = $request->get('reference');
            Log::debug('reference is ' . $ref);
            Log::debug('Fetching Vehicle Details ' . $ref);

            if (empty($ref)) {
                throw new BadRequestException(
                    'Missing Vehicle Reference'
                );
            }

            $vehicle = $this->vehicleDetailsService->getVehicleDetailsById($ref);

            if (!empty(!$vehicle)) {
                Log::info('Vehicle Details Found ');
            } else {
                Log::info('Vehicle Details Not Found ');
            }

            $vehicleDocuments = $this->vehicleDetailsService->getVehicleDocuments($ref);

            $enteredAccessories = VehicleAccessory::where(
                'vehicle_header_id',
                '=',
                (int)$ref
            )->get();

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
                    ? self::VEHICLE_DETAILS_RETRIEVED_SUCCESSFULLY
                    : 'Could not read vehicle details'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = 'Missing Required Parameters';

            if ($e instanceof BadRequestException
                || $e instanceof DataNotFoundException) {
                $message = ErrorMessages::getMessage('err_0005');
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }

    public function getVehicleDetailsByRegistration(Request $request): JsonResponse
    {
        try {
            $registrationNumber = $request->get('vehicle_registration');

            list($vehicle, $vehicle_state) = $this->getVehicleStateDetails(
                $registrationNumber
            );

            $vehicleImages = $this->vehicleDetailsService->getVehicleImages($vehicle->vehicle_header_id);

            $accessories = $this->vehicleDetailsService->getSubmittedAccessories($vehicle->vehicle_header_id);

            $article = $this->procurementService->getArticleByCode($vehicle->fuel_types);

            list($insuranceState, $insurance) = $this->insuranceService->getCheckInsurance($registrationNumber);

            list($roadTaxState, $roadTax) = $this->roadTaxService->getRoadLicence($registrationNumber);

            list($fitnessState, $fitnessRecord) = $this->vehicleFitnessService->getFitness($registrationNumber);

            Log::info("Insurance State $insuranceState->value");

            $hasValidInsurance = true;
            $vehicleInsuranceMessage = '';
            if ($insuranceState->value == DocumentState::Expired->value) {
                $hasValidInsurance = false;
                $vehicleInsuranceMessage = str_replace(
                    self::REG,
                    $vehicle->registration_number,
                    ErrorMessages::getMessage('err_0030')
                );
            }

            $hasValidRoadTax = true;
            $vehicleRoadTaxMessage = '';
            if ($roadTaxState->value == DocumentState::Expired->value) {
                $hasValidRoadTax = false;
                $vehicleRoadTaxMessage = str_replace(
                    self::REG,
                    $vehicle->registration_number,
                    ErrorMessages::getMessage('err_0031')
                );
            }

            $hasValidFitness = true;
            $vehicleFitnessMessage = '';
            if ($fitnessState->value == DocumentState::Expired->value) {
                $hasValidFitness = false;
                $vehicleFitnessMessage = str_replace(
                    self::REG,
                    $vehicle->registration_number,
                    ErrorMessages::getMessage('err_0032')
                );
            }


            $vehicleTomCardMessage = '';
            if ($vehicle->has_tom_card === 'Y') {
                $vehicleTomCardMessage = str_replace(
                    self::REG,
                    $vehicle->registration_number,
                    ErrorMessages::getMessage('err_0023')
                );
            }

            //payload show be on class
            return response()->json(
                FleetMasterJsonResponse::response(
                    !empty($vehicle) ? 'success' : 'failure',
                    !empty($vehicle),
                    self::VEHICLE_DETAILS_RETRIEVED_SUCCESSFULLY,
                    [
                        'vehicle' => $vehicle,
                        'article' => $article,
                        'images' => $vehicleImages,
                        'accessories' => $accessories,
                        'vehicle_state' => $vehicle_state,
                        'vehicle_tom_card_message' => $vehicleTomCardMessage,

                        'hasValidInsurance' => $hasValidInsurance,
                        'insuranceMessage' => $vehicleInsuranceMessage,
                        'insurance' => $insurance,

                        'hasValidRoadTax' => $hasValidRoadTax,
                        'roadTaxMessage' => $vehicleRoadTaxMessage,
                        'roadTax' => $roadTax,

                        'hasValidFitness' => $hasValidFitness,
                        'fitnessMessage' => $vehicleFitnessMessage,
                        'fitness' => $fitnessRecord
                    ]
                )
            );

        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage('err_0005');
            if ($e instanceof BadRequestException
                || $e instanceof DataNotFoundException) {
                $message = $e->getMessage();
            }
            return response()->json(
                FleetMasterJsonResponse::response(
                    'failure',
                    false,
                    $message
                )
            );
        }
    }

    public function getVehicleDetails(Request $request): JsonResponse
    {
        try {

            $registrationNumber = $request->get('vehicle_registration');

            list($vehicle, $vehicle_state) = $this->getVehicleStateDetails(
                $registrationNumber
            );

            return response()->json([
                'payload' => [
                    'vehicle' => $vehicle,
                    'vehicle_state' => $vehicle_state
                ],
                'success' => !empty($vehicle),
                'message' => self::VEHICLE_DETAILS_RETRIEVED_SUCCESSFULLY
            ]);

        } catch (Exception $e) {
            Log::error($e);
            $message = ErrorMessages::getMessage(
                'err_0005'
            );
            if ($e instanceof BadRequestException
                || $e instanceof DataNotFoundException) {
                $message = $e->getMessage();
            }
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }

    public function list(Request $request): string
    {
        Log::info('Has Get Records' . $request->has('getRecords'));
        if ($request->has('getRecords')) {
            Log::debug("Get Records Present");
            $vehicleList = $this->vehicleDetailsService->getFilteredVehiclesInformation($request);
        } else {
            $vehicleList = $this->vehicleDetailsService->getAllVehicles();
        }

        $statusList = Status::where(
            'module',
            '=',
            Modules::VEHICLE->value)
            ->get();

        return view('modules.vehicleManagement.vehicleList')
            ->with(compact('vehicleList',
                    'statusList')
            );
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

    /**
     * @param string $registrationNumber
     * @return array
     * @throws DataNotFoundException
     */
    public function getVehicleStateDetails(string $registrationNumber): array
    {
        if (empty($registrationNumber)) {
            throw new BadRequestException(
                'Missing required parameter'
            );
        }

        // determine material type in form of fuel
        $vehicle = $this->vehicleDetailsService->getBasicVehicleDetails(
            $registrationNumber
        );

        if (empty($vehicle)) {
            throw new DataNotFoundException(
                'Vehicle not found'
            );
        }

        $vehicle_state = '';
        if ($vehicle->on_boarding_status != StatusHelper::onboardingComplete()) {
            $vehicle_state = str_replace(
                self::REG,
                $vehicle->registration_number,
                SystemMessages::vehiclePendingOnboardingCompletion()
            );
        } elseif ($vehicle->status == StatusHelper::vehicleInWorkshop()) {
            $jobCard = JobCardHeader::where('reg_no',
                '=',
                $vehicle->registration_number)->first();

            $workshopName = "";
            if (!empty($jobCard) && !empty($jobCard->workshop_code)) {
                $workshopName = WorkShop::where('workshop_code', $jobCard->workshop_code)
                    ->first()->workshop_name;
            }

            $vehicle_state = str_replace(self::REG,
                $vehicle->registration_number,
                str_replace("@workshop",
                    $workshopName,
                    SystemMessages::vehicleInWorkshop())
            );
        } elseif ($vehicle->status != StatusHelper::active()) {
            $vehicle_state = str_replace(self::REG,
                $vehicle->registration_number,
                str_replace("@state",
                    $vehicle->status_name,
                    ErrorMessages::getMessage('err_0029'))
            );
        }
        return array($vehicle, $vehicle_state);
    }
}
