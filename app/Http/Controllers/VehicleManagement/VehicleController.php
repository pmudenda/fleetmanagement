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
use App\Services\VehicleManagement\VehicleAnalyticsService;
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
    private VehicleAnalyticsService $analyticsService;

    public function __construct(
        VehicleDetailsService               $vehicleDetailsService,
        ProcurementSystemIntegrationService $procurementService,
        InsuranceService                    $insuranceService,
        RoadTaxService                      $roadTaxService,
        FitnessService                      $vehicleFitnessService,
        VehicleAnalyticsService             $analyticsService
    )
    {
        $this->vehicleDetailsService = $vehicleDetailsService;
        $this->procurementService = $procurementService;
        $this->insuranceService = $insuranceService;
        $this->roadTaxService = $roadTaxService;
        $this->vehicleFitnessService = $vehicleFitnessService;
        $this->analyticsService = $analyticsService;
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

    public function list(Request $request)
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
        $fuelCostByMonth = [];
        $sparesCostByMonth = [];
        
        try {
            // Get maintenance/spares costs by month with comprehensive joins
            $sparesResults = DB::table('fleetmaster.gen_material_details as d')
                ->join('fleetmaster.gen_material_headers as h', 'h.REQ_NO', '=', 'd.REQ_NO')
                ->join('fleetmaster.vm_vehicle_header as g', 'd.reg_no', '=', 'g.REGISTRATION_NUMBER')
                ->join('ZFM_ARTICLES_VIEW as a', 'd.MATERIAL_CODE', '=', 'a.code_article')
                ->join('fleetmaster.vm_engine_details as ed', 'g.REGISTRATION_NUMBER', '=', 'ed.reg_no')
                ->join('fleetmaster.vm_assignments as va', 'g.REGISTRATION_NUMBER', '=', 'VA.REG_NO')
                ->join('fleetmaster.tms_data_clean_up as td', 'g.REGISTRATION_NUMBER', '=', 'td.REGISTRATIONNUMBER')
                ->join('store_movements_header as mh', 'h.st_pur', '=', 'mh.stores_requisition_no')
                ->join('fleetmaster.gps as gps', 'g.REGISTRATION_NUMBER', '=', 'gps.REG_NUMBER')
                ->where('d.reg_no', QueryComparisonOperator::EQUALS, $registrationNumber)
                ->where('h.status', 'IN', ['26', '32', '42', '46'])
                ->where('h.IS_FUEL', '=', 'N')
                ->select(
                    DB::raw('SUM(d.QUANTITY * d.PRICE) as cost'),
                    DB::raw('EXTRACT(YEAR FROM h.DATE_CREATED) as year'),
                    DB::raw('EXTRACT(MONTH FROM h.DATE_CREATED) as month'),
                    DB::raw('TO_CHAR(h.DATE_CREATED, \'YYYY-MM\') as period'),
                    DB::raw('ed.engine_brand || \' \' || g.model_name as vehicle_type'),
                    DB::raw('va.BUSINESS_UNIT_NAME || \' \' || va.COST_CENTER_NAME as vehicle_assignment'),
                    'td.ORGANIZATIONALUNIT',
                    'h.st_pur as requi_number',
                    'mh.document_no as issue_no',
                    'h.document_no as job_card_no',
                    'd.MATERIAL_CODE as article_code',
                    'a.description as article_description'
                )
                ->groupBy(
                    DB::raw('EXTRACT(YEAR FROM h.DATE_CREATED)'),
                    DB::raw('EXTRACT(MONTH FROM h.DATE_CREATED)'),
                    DB::raw('TO_CHAR(h.DATE_CREATED, \'YYYY-MM\')'),
                    'ed.engine_brand', 'g.model_name', 'va.BUSINESS_UNIT_NAME', 'va.COST_CENTER_NAME', 'td.ORGANIZATIONALUNIT',
                    'h.st_pur', 'mh.document_no', 'h.document_no', 'd.MATERIAL_CODE', 'a.description'
                )
                ->orderBy(DB::raw('EXTRACT(YEAR FROM h.DATE_CREATED)'))
                ->orderBy(DB::raw('EXTRACT(MONTH FROM h.DATE_CREATED)'))
                ->get();
                
            $sparesCostByMonth = $sparesResults->toArray();
            
        } catch (Exception $e) {
            Log::debug("Fetching Vehicle Maintenance Report Data");
            Log::error($e);
        }

        try {
            // Get fuel costs by month
            $fuelResults = DB::table('fleetmaster.fuel_management')
                ->where('reg_no', QueryComparisonOperator::EQUALS, $registrationNumber)
                ->select(
                    DB::raw('SUM(amount) as cost'),
                    DB::raw('EXTRACT(YEAR FROM FECH_ACT) as year'),
                    DB::raw('EXTRACT(MONTH FROM FECH_ACT) as month'),
                    DB::raw('TO_CHAR(FECH_ACT, \'YYYY-MM\') as period')
                )
                ->groupBy(
                    DB::raw('EXTRACT(YEAR FROM FECH_ACT)'),
                    DB::raw('EXTRACT(MONTH FROM FECH_ACT)'),
                    DB::raw('TO_CHAR(FECH_ACT, \'YYYY-MM\')')
                )
                ->orderBy(DB::raw('EXTRACT(YEAR FROM FECH_ACT)'))
                ->orderBy(DB::raw('EXTRACT(MONTH FROM FECH_ACT)'))
                ->get();
                
            $fuelCostByMonth = $fuelResults->toArray();
            
        } catch (Exception $e) {
            Log::debug("Fetching Vehicle Fuel Report Data");
            Log::error($e);
        }

        return array($fuelCostByMonth, $sparesCostByMonth);
    }

    /**
     * Get comprehensive maintenance details for a vehicle
     */
    public function getMaintenanceDetails(Request $request): JsonResponse
    {
        try {
            $registrationNumber = $request->get('registration_number');
            $months = $request->get('months', 12);

            if (empty($registrationNumber)) {
                throw new BadRequestException('Missing registration number');
            }

            $maintenanceDetails = $this->analyticsService->getMaintenanceDetails($registrationNumber, $months);

            return response()->json([
                'success' => true,
                'data' => $maintenanceDetails,
                'message' => 'Maintenance details retrieved successfully'
            ]);
        } catch (Exception $e) {
            Log::error("Error fetching maintenance details: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve maintenance details'
            ], 500);
        }
    }

    /**
     * Get executive KPI summary for dashboard
     */
    public function getDashboardKpi(Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 365); // Use 365 days for more data
            $kpiData = $this->analyticsService->getExecutiveKpiSummaryWorking($days);

            return response()->json([
                'success' => true,
                'data' => $kpiData,
                'message' => 'KPI summary retrieved successfully'
            ]);
        } catch (Exception $e) {
            Log::error("Error fetching dashboard KPI: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve KPI summary'
            ], 500);
        }
    }

    /**
     * Get monthly trend data for charts
     */
    public function getMonthlyTrends(Request $request): JsonResponse
    {
        try {
            $months = $request->get('months', 12);
            $trendData = $this->analyticsService->getMonthlyTrends($months);

            return response()->json([
                'success' => true,
                'data' => $trendData,
                'message' => 'Monthly trends retrieved successfully'
            ]);
        } catch (Exception $e) {
            Log::error("Error fetching monthly trends: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve monthly trends'
            ], 500);
        }
    }

    /**
     * Get top vehicles by metric
     */
    public function getTopVehiclesByMetric(Request $request): JsonResponse
    {
        try {
            $metric = $request->get('metric', 'total_cost');
            $limit = $request->get('limit', 10);
            $days = $request->get('days', 30);

            $topVehicles = $this->analyticsService->getTopVehiclesByMetric($metric, $limit, $days);

            return response()->json([
                'success' => true,
                'data' => $topVehicles,
                'message' => 'Top vehicles retrieved successfully'
            ]);
        } catch (Exception $e) {
            Log::error("Error fetching top vehicles by metric: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve top vehicles'
            ], 500);
        }
    }

    /**
     * Get cost distribution by organizational unit
     */
    public function getCostDistribution(Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 30);
            $distribution = $this->analyticsService->getCostDistributionByOrgUnit($days);

            return response()->json([
                'success' => true,
                'data' => $distribution,
                'message' => 'Cost distribution retrieved successfully'
            ]);
        } catch (Exception $e) {
            Log::error("Error fetching cost distribution: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cost distribution'
            ], 500);
        }
    }

    /**
     * Get fleet exceptions and alerts
     */
    public function getFleetExceptions(Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 30);
            $exceptions = $this->analyticsService->getFleetExceptions($days);

            return response()->json([
                'success' => true,
                'data' => $exceptions,
                'message' => 'Fleet exceptions retrieved successfully'
            ]);
        } catch (Exception $e) {
            Log::error("Error fetching fleet exceptions: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fleet exceptions'
            ], 500);
        }
    }

    /**
     * Get top vehicles by various metrics
     */
    public function getTopVehiclesAnalytics(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $metric = $request->get('metric', 'operating_cost');

            $data = [];
            switch ($metric) {
                case 'fuel_consumption':
                    $data = $this->analyticsService->getTopVehiclesByFuelConsumption($limit);
                    break;
                case 'maintenance_cost':
                    $data = $this->analyticsService->getTopVehiclesByMaintenanceCost($limit);
                    break;
                case 'operating_cost':
                default:
                    $data = $this->analyticsService->getTopVehiclesByOperatingCosts($limit);
                    break;
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'metric' => $metric,
                'limit' => $limit
            ]);
        } catch (Exception $e) {
            Log::error("Error fetching top vehicles analytics: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch analytics data'
            ], 500);
        }
    }

    /**
     * Get vehicle performance trends and behavior patterns
     */
    public function getVehiclePerformanceAnalytics(Request $request): JsonResponse
    {
        try {
            $registrationNumber = $request->get('registration_number');
            $months = $request->get('months', 12);

            if (empty($registrationNumber)) {
                throw new BadRequestException('Vehicle registration number is required');
            }

            $trends = $this->analyticsService->getVehiclePerformanceTrends($registrationNumber, $months);
            $patterns = $this->analyticsService->getVehicleBehaviorPatterns($registrationNumber);

            return response()->json([
                'success' => true,
                'data' => [
                    'trends' => $trends,
                    'patterns' => $patterns,
                    'registration_number' => $registrationNumber,
                    'analysis_period_months' => $months
                ]
            ]);
        } catch (Exception $e) {
            Log::error("Error fetching vehicle performance analytics: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch performance analytics'
            ], 500);
        }
    }

    /**
     * Get fleet-wide analytics summary
     */
    public function getFleetAnalyticsSummary(Request $request): JsonResponse
    {
        try {
            $summary = $this->analyticsService->getFleetAnalyticsSummary();

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (Exception $e) {
            Log::error("Error fetching fleet analytics summary: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch fleet analytics'
            ], 500);
        }
    }

}
