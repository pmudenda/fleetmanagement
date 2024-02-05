<?php

namespace App\Http\Controllers\VehicleManagement;

use App\Constants\ErrorMessages;
use App\Constants\QueryComparisonOperator;
use App\Constants\TableColumns;
use App\Enums\DocumentState;
use App\Enums\Modules;
use App\Exceptions\DataNotFoundException;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Responses\FleetMasterJsonResponse;
use App\Models\Settings\Accessory;
use App\Models\Settings\general\Status;
use App\Models\VehicleManagement\EngineDetail;
use App\Models\VehicleManagement\VehicleAccessory;
use App\Services\Integration\ProcurementSystemIntegrationService;
use App\Services\VehicleManagement\FitnessService;
use App\Services\VehicleManagement\FuelAllocationService;
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

            if (!empty($vehicle)) {
                Log::debug('Vehicle Details Found ');
            } else {
                Log::debug('Vehicle Details Not Found ');
            }

            $vehicleDocuments = $this->vehicleDetailsService->getVehicleDocuments($ref);

            $enteredAccessories = VehicleAccessory::where(
                'vehicle_header_id',
                QueryComparisonOperator::EQUALS,
                (int)$ref
            )->get();

            list($fuelCostByYear, $sparesCostByYear) = $this->getVehicleOperationCosts($vehicle->registration_number);
            return response()->json([
                'payload' => [
                    'vehicle' => $vehicle,
                    'documents' => $vehicleDocuments,
                    'enteredAccessories' => $enteredAccessories,
                    'cost_by_year' => $fuelCostByYear,
                    'spares_cost_by_year' => $sparesCostByYear
                ],
                'success' => !empty($vehicle),
                'message' => !empty($vehicle)
                    ? self::VEHICLE_DETAILS_RETRIEVED_SUCCESSFULLY
                    : 'Could not read vehicle details'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $message = 'Failed to retrieve Registration Details';

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

    public function getVehicleReportsOverView(string $registrationNumber): JsonResponse
    {
        try {
            Log::debug('reference is ' . $registrationNumber);
            Log::debug('Fetching Vehicle Details ' . $registrationNumber);

            if (empty($registrationNumber)) {
                throw new BadRequestException(
                    'Missing Vehicle Reference'
                );
            }

            list($fuelCostByYear, $sparesCostByYear) = $this->getVehicleOperationCosts($registrationNumber);

            return response()->json(
                FleetMasterJsonResponse::response(
                    '',
                    true,
                    '',
                    [
                        'cost_by_year' => $fuelCostByYear,
                        'spares_cost_by_year' => $sparesCostByYear
                    ]
                )
            );
        } catch (Exception $e) {
            Log::error($e);
            $message = 'Failed to retrieve Registration Details';

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
            list($vehicle, $vehicleState) = $this->getVehicleStateDetails(
                $registrationNumber
            );
            $vehicleImages = $this->vehicleDetailsService->getVehicleImages($vehicle->vehicle_header_id);

            $accessories = $this->vehicleDetailsService->getSubmittedAccessories($vehicle->vehicle_header_id);

            $article = $this->procurementService->getArticleByCode($vehicle->fuel_types);

            $engine_details = EngineDetail::where('reg_no', $registrationNumber)->first();

            list($insuranceState, $insurance) = $this->insuranceService->getCheckInsurance($registrationNumber);

            list($roadTaxState, $roadTax) = $this->roadTaxService->getRoadLicence($registrationNumber);

            list($fitnessState, $fitnessRecord) = $this->vehicleFitnessService->getFitness($registrationNumber);

            Log::debug("Insurance State $insuranceState->value");

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
                        'vehicle_state' => $vehicleState,
                        'vehicle_tom_card_message' => $vehicleTomCardMessage,

                        'hasValidInsurance' => $hasValidInsurance,
                        'insuranceMessage' => $vehicleInsuranceMessage,
                        'insurance' => $insurance,

                        'hasValidRoadTax' => $hasValidRoadTax,
                        'roadTaxMessage' => $vehicleRoadTaxMessage,
                        'roadTax' => $roadTax,

                        'hasValidFitness' => $hasValidFitness,
                        'fitnessMessage' => $vehicleFitnessMessage,
                        'fitness' => $fitnessRecord,
                        'engine' => $engine_details
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

            list($vehicle, $vehicleState) = $this->getVehicleStateDetails(
                $registrationNumber
            );

            return response()->json([
                'payload' => [
                    'vehicle' => $vehicle,
                    'vehicle_state' => $vehicleState
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
        Log::debug('Has Get Records' . $request->has('getRecords'));

        if ($request->has('getRecords')) {

            Log::debug("Get Records Present");

            $vehicleList = $this->vehicleDetailsService->getFilteredVehiclesInformationQuery($request)->paginate(10);
        } else {
            $vehicleList = $this->vehicleDetailsService->getAllVehiclesQuery()->paginate(10);
        }

        $statusList = Status::where(
            'module',
            QueryComparisonOperator::EQUALS,
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

    public function register(): View
    {
        return view('modules.vehicleManagement.vehicleList');
    }

    public function accessories(): View
    {
        $accessories = Accessory::where(
            TableColumns::STATUS,
            QueryComparisonOperator::EQUALS,
            StatusHelper::active())
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
        return $this->vehicleDetailsService->getVehicleStateDetails(
            $registrationNumber
        );
    }

    /**
     * @param string $registrationNumber
     * @return array
     */
    public function getVehicleOperationCosts(string $registrationNumber): array
    {
        $fuelCostByYear = [];
        $sparesCostByYear = [];
        try {
            DB::table('zfm_spare_cost')
                ->where('reg_no',
                    QueryComparisonOperator::EQUALS,
                    $registrationNumber)
                ->select(DB::raw('SUM(value_amount) as cost, EXTRACT(YEAR FROM TO_DATE(document_date)) year'))
                ->groupBy(DB::raw('EXTRACT(YEAR FROM TO_DATE(document_date))'))
                ->orderBy(DB::raw('EXTRACT(YEAR FROM TO_DATE(document_date))'))
                ->get();
        } catch (Exception $e) {
            Log::debug("Fetching Vehicle Spares Report Data");
            Log::error($e);
        }

        try {
            DB::table('zfm_fuel_cost')
                ->where('reg_no',
                    QueryComparisonOperator::EQUALS,
                    $registrationNumber)
                ->select(DB::raw('SUM(ttl) as cost,year'))
                ->groupBy('year')
                ->orderBy('year')
                ->get();
        } catch (Exception $e) {
            Log::debug("Fetching Vehicle Maintenance Report Data");
            Log::error($e);
        }

        return array($fuelCostByYear, $sparesCostByYear);
    }

}
