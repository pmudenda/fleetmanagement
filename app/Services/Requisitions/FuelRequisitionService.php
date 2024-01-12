<?php

namespace App\Services\Requisitions;

use App\Constants\Accounts;
use App\Constants\Articles;
use App\Constants\CostAssignment;
use App\Constants\ErrorMessages;
use App\Constants\QueryComparisonOperator;
use App\Constants\TableColumns;
use App\Constants\WorkflowActions;
use App\Constants\WorkflowModules;
use App\Enums\ApprovalStage;
use App\Enums\Modules;
use App\Enums\RequisitionTypes;
use App\Enums\WorkflowProcessCodes;
use App\Events\FuelRequisitionWorkflowUpdate;
use App\Events\RequisitionRaised;
use App\Events\RequisitionResubmitted;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\NoOdometerEntryException;
use App\Exceptions\OrganisationUnitStateException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Helpers\TaskStatus;
use App\Http\Requests\FuelRequisitionPostRequest;
use App\Http\Requests\FuelRequisitionUpdate;
use App\Models\Common\MaterialDetail;
use App\Models\Common\MaterialHeader;
use App\Models\Common\OrganizationalUnit;
use App\Services\FileUploads\FileUploadService;
use App\Services\Integration\ProcurementSystemIntegrationService;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\Workflow\WorkflowService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class FuelRequisitionService {
    const REQ_NO = "@req_no";
    const ODOMETER = "@odometer";
    const DATE_VALID_TO = "@date_valid_to";
    const VEH_REG = "@veh_reg";
    const DATE_FORMAT = "d/m/Y";
    const APPROVED = 'Request Approved and Submitted to the Next Authority For Approval ';
    const REQUISITION_TYPE = "requisition_type";

    private WorkflowService $workflowService;
    private ProcurementSystemIntegrationService $procurementService;
    private FuelRequisitionValidationService $validationService;
    private VehicleAssignmentValidationService $vehicleAssignmentStateValidateService;

    public function __construct(
        WorkflowService                     $workflowService,
        ProcurementSystemIntegrationService $procurementService,
        FuelRequisitionValidationService    $validationService,
        VehicleAssignmentValidationService  $vehicleAssignmentStateValidateService) {
        $this->workflowService = $workflowService;
        $this->procurementService = $procurementService;
        $this->validationService = $validationService;
        $this->vehicleAssignmentStateValidateService = $vehicleAssignmentStateValidateService;
    }

    /**
     * @throws FuelRequisitionException|WorkflowTaskCreationFailedException
     * @throws NoOdometerEntryException
     * @throws OrganisationUnitStateException
     */
    public function processRequest(FuelRequisitionPostRequest $requisitionPostRequest): JsonResponse {
        $isOutOfTownRequisition = $requisitionPostRequest->get(
                self::REQUISITION_TYPE) == RequisitionTypes::OutOfTown->value;

        $isLocalRequisition = $requisitionPostRequest->get(
                self::REQUISITION_TYPE) == RequisitionTypes::Normal->value;

        $isOverrideRequisition = $requisitionPostRequest->get(
                self::REQUISITION_TYPE) == RequisitionTypes::Override->value;

        $registrationNumber = $requisitionPostRequest->get("vehicle_registration");

        $this->validateVehicleStatus($registrationNumber);

        $this->verifyUserUnitState($requisitionPostRequest->get("cost_centre_code"));

        $latestOdometerLogsMaxOdometer = $this->getLatestOdometerLogsEntry($registrationNumber);

        Log::debug("Last Odometer Log $latestOdometerLogsMaxOdometer");
        [$quantityLastIssued, $latestIssue] = $this->getFuelLastIssue($registrationNumber);
        Log::debug("Latest Issued Amount $quantityLastIssued");

        $odometerOnLastIssue = $this->getOdometerOnLastIssue($registrationNumber);

        $this->vehicleAssignmentStateValidateService
            ->checkVehicleAssignedUserUnitAndBuCcStatus($registrationNumber);

        [$fuel_consumption, $tank_capacity] = $this->getVehicleFuelConsumptionData($registrationNumber);

        // check that current user provided odometer is greater than last issue
        $userProvidedOdometer = $requisitionPostRequest->get('odometer_reading');

        if (!empty($latestIssue)) {
            $this->validationService->validateOdometerAgainstLastIssue(
                $latestIssue,
                $userProvidedOdometer,
                $odometerOnLastIssue,
                $registrationNumber
            );
        }

        // check that current user provided odometer is greater than last issue
        $this->validationService->validateCurrentOdometerAgainstMileageReturn(
            $latestOdometerLogsMaxOdometer,
            $userProvidedOdometer
        );

        $latestActiveRequisition = self::getLatestActiveRequisition($registrationNumber);

        // pick last requisition if any
        $openRequisitionStatusList = [
            StatusHelper::new(),
            StatusHelper::resubmitted(),
            StatusHelper::partiallyReleased(),
            StatusHelper::authorised(),
            StatusHelper::partiallyAuthorised()
        ];

        if (!empty($latestActiveRequisition)) {
            Log::debug("Status of Previous Requisition for
                $registrationNumber
                has
                $latestActiveRequisition->status status");
        } else {
            Log::debug("No Previous Requisition for
            $registrationNumber
            . found");
        }

        $validFrom = Carbon::createFromFormat(
            self::DATE_FORMAT,
            $requisitionPostRequest->get("request_date")
        );

        $validTo = Carbon::createFromFormat(
            self::DATE_FORMAT,
            $requisitionPostRequest->get("next_fuel_date")
        );

        if ($isLocalRequisition) {
            // quantity requested can not be more than allocated
            $this->validationService->validateLocalRequisition(
                $requisitionPostRequest,
                $latestActiveRequisition,
                $openRequisitionStatusList,
                $registrationNumber,
                $validFrom
            );
        } elseif ($isOutOfTownRequisition) {
            list($validFrom, $validTo) =
                $this->validationService->validateOutOfTown(
                    $requisitionPostRequest,
                    $latestActiveRequisition,
                    $openRequisitionStatusList
                );

        } elseif ($isOverrideRequisition) {

            $this->validationService->validateOverride(
                $latestActiveRequisition,
                $openRequisitionStatusList,
                $registrationNumber,
                $validFrom,
                $requisitionPostRequest
            );

            /* override is only valid from date of request to when the
            original requisition was supposed to end */
            Log::debug("Previous Requisition End Date
                $latestActiveRequisition->valid_date_to");

            $validFrom = Carbon::now();
            $validTo = $latestActiveRequisition->valid_date_to;
        }


        Log::debug("Calculating Maximum Distance that should have been covered by
                   $registrationNumber");
        Log::debug('Consumption ' . $fuel_consumption);
        Log::debug('Quantity Last Issued ' . $quantityLastIssued);
        $maximumDistance = ($quantityLastIssued * ($fuel_consumption)) * 0.25;
        $minDistance  = $maximumDistance * 0.25;
        $newEstimatedOdometer = $maximumDistance + $odometerOnLastIssue;
//        dd(compact('maximumDistance','newEstimatedOdometer','userProvidedOdometer'));
        Log::debug("Maximum Distance " . $maximumDistance);
        Log::debug("Odometer Last Issue " . $odometerOnLastIssue);
        Log::debug("Last Issue + Odometer On Last Issue " . $newEstimatedOdometer);

        // check the value of deviation 5 - 8 = -3
//        $variance = $userProvidedOdometer - $newEstimatedOdometer;

        $variance = $userProvidedOdometer - ($minDistance + $odometerOnLastIssue);
        Log::debug("Odometer Variance " . $variance);

        if ($variance < 0) {
            throw new FuelRequisitionException(
                str_replace(
                    "@cur_odometer",
                    $userProvidedOdometer,
                    str_replace(self::ODOMETER,
                        $latestIssue->odometer,
                        str_replace(self::REQ_NO,
                            $latestIssue->st_pur ?? $latestIssue->req_no,
                            ErrorMessages::getMessage('err_0025')
                        )
                    )
                )
            );
        }

        if (!empty($latestIssue)) {
            Log::debug("Odometer Used On Last Request  $latestIssue->odometer");
            // Maximum Distance You can Travel Before Issue
            // [Mdbi] = [Tank Capacity - Quantity On Last Issue] * Fuel Consumption
            // Maximum Distance You can With Issue [Mdwi] =
            // [Odometer On Last Issue + ( Quantity On Last Issue * Fuel Consumption )]
            // Maximum Odometer Acceptable (Moa) = [Mdbi]  + [Mdwi];
            $maximumOdometerAcceptable = ($odometerOnLastIssue + ($quantityLastIssued * $fuel_consumption));
            Log::debug("Maximum Acceptable Based On Last Issued Amount $maximumOdometerAcceptable");

            if ($quantityLastIssued < $tank_capacity) {
                $distanceTravelledOnAmountInTank = (($tank_capacity - $quantityLastIssued) * $fuel_consumption);
                Log::debug("Distance Travelled On what may have been in tank $distanceTravelledOnAmountInTank");
                $maximumOdometerAcceptable += $distanceTravelledOnAmountInTank;
            }

            Log::debug("Total Maximum Acceptable $maximumOdometerAcceptable
                 vs What User Provided $userProvidedOdometer");

            if ($userProvidedOdometer > $maximumOdometerAcceptable) {
                throw new FuelRequisitionException(
                    str_replace("@cur_odometer",
                        $userProvidedOdometer,
                        str_replace(self::ODOMETER,
                            $latestIssue->odometer,
                            str_replace(self::REQ_NO,
                                $latestIssue->st_pur ?? $latestIssue->req_no,
                                ErrorMessages::getMessage('err_0026')
                            )
                        )
                    )
                );
            }
        }

        Log::debug("Vehicle Reg Is $registrationNumber");

        $requisition_reference_number = $this->saveFuelRequisition(
            $requisitionPostRequest,
            $registrationNumber,
            $validFrom,
            $validTo);

        return response()->json([
            "success" => true,
            "message" => "Requisition Submitted For Approval. Requisition Number " . $requisition_reference_number,
            "redirectUrl" => URL::signedRoute("show.fuel.requisition", ["ref" => $requisition_reference_number])
        ]);
    }

    /**
     * Verifies Vehicle is Active otherwise throws exception
     * @throws FuelRequisitionException
     */
    public function validateVehicleStatus($reference): void {
        $allowedStatus = [StatusHelper::active()];

        $count = DB::table('vm_vehicle_header header')
            ->where(
                TableColumns::VEHICLE_REGISTRATION,
                QueryComparisonOperator::EQUALS,
                $reference
            )->whereIn(TableColumns::STATUS, $allowedStatus)
            ->select(
                'header.*'
            )->count();

        if ($count == 0) {
            throw new FuelRequisitionException(
                ErrorMessages::getMessage(
                    "err_0004"
                ),
                1000
            );
        }

    }

    /**
     * @param $req_no
     * @return Model|Builder|object|null
     */
    public function getRequisitionDetail($req_no): mixed {
        $results = DB::table("GEN_MATERIAL_HEADERS mat_header")
            ->where("mat_header.req_no", $req_no)
            ->join("GEN_MATERIAL_DETAILS detail",
                "mat_header.req_no",
                QueryComparisonOperator::EQUALS,
                "detail.req_no")
            ->leftJoin("CONFIG_STATUSES status",
                "mat_header.status",
                QueryComparisonOperator::EQUALS,
                "status.code")
            ->leftJoin("SEC_USERS users",
                "mat_header.requested_by",
                QueryComparisonOperator::EQUALS,
                "users.staff_no")
            ->where("status.MODULE",
                QueryComparisonOperator::EQUALS,
                Modules::MATERIAL)
            ->select(
                "mat_header.*",
                "detail.*",
                'users.name as originator',
                'users.job_title',
                "status.name as status_name",
                "status.color_code")
            ->get();

        return $results->first();

    }

    /**
     * @throws FuelRequisitionException
     */
    public function createStoresRequisition(string $reference): string {
        $requisitionDetail = self::getRequisitionDetail($reference);

        if ($requisitionDetail->cost_assigned_to === 'CostCenter') {
            $results = $this->procurementService->createStoresRequisition(
                $reference,
                $requisitionDetail->veh_reg_no,
                $requisitionDetail->form_order,
                Accounts::MOTOR_VEHICLE_FUEL_LUBRICANTS_ACCOUNT
            );
        } else {
            $results = $this->procurementService->createStoresRequisition(
                $reference,
                $requisitionDetail->veh_reg_no,
                $requisitionDetail->form_order,
                Accounts::MOTOR_VEHICLE_PROJECTS_FUEL_ACCOUNT
            );
        }

        if (empty($results)) {
            throw new FuelRequisitionException(
                ErrorMessages::getMessage("err_0021")
            );
        }

        if (!str_contains($results, "J01")) {
            throw new FuelRequisitionException($results);
        }

        Log::debug("Stores Requisition Generated with document " . $results);

        return $results;
    }

    public function getLatestRequisition($vehicle_registration) {
        $queryResult = DB::table("GEN_MATERIAL_HEADERS as mat_header")
            ->leftJoin("CONFIG_STATUSES as status",
                "mat_header.status",
                QueryComparisonOperator::EQUALS,
                "status.code")
            ->leftJoin("CONFIG_REQUISITION_TYPES req_type",
                "mat_header.requisition_type",
                QueryComparisonOperator::EQUALS,
                "req_type.code")
            ->where("mat_header.veh_reg_no",
                QueryComparisonOperator::EQUALS,
                $vehicle_registration
            )
            ->select("mat_header.*",
                "status.name as status_name",
                "req_type.name as requisition_type"
            )
            ->orderBy("mat_header.created_at", "desc")
            ->get();

        return empty($queryResult) ? [] : $queryResult->first();
    }

    public function getMyRequisitions($staff_no, $search = false): Collection {
        if ($staff_no) {
            return DB::table("GEN_MATERIAL_HEADERS")
                ->leftJoin("GEN_MATERIAL_DETAILS",
                    "GEN_MATERIAL_HEADERS.req_no",
                    QueryComparisonOperator::EQUALS,
                    "GEN_MATERIAL_DETAILS.req_no")
                ->leftJoin("CONFIG_STATUSES",
                    "GEN_MATERIAL_HEADERS.status",
                    QueryComparisonOperator::EQUALS,
                    "CONFIG_STATUSES.code")
                ->leftJoin("CONFIG_REQUISITION_TYPES",
                    "GEN_MATERIAL_HEADERS.requisition_type",
                    QueryComparisonOperator::EQUALS,
                    "CONFIG_REQUISITION_TYPES.code")
                ->leftJoin("SEC_USERS",
                    "GEN_MATERIAL_HEADERS.requested_by",
                    QueryComparisonOperator::EQUALS,
                    "SEC_USERS.staff_no")
                ->where("GEN_MATERIAL_HEADERS.requested_by",
                    QueryComparisonOperator::EQUALS,
                    $staff_no)
                ->where("CONFIG_STATUSES.MODULE",
                    QueryComparisonOperator::EQUALS,
                    Modules::MATERIAL->value)
                ->where("GEN_MATERIAL_HEADERS.IS_FUEL",
                    QueryComparisonOperator::EQUALS,
                    "Y"
                )->select(
                    "GEN_MATERIAL_HEADERS.*",
                    "GEN_MATERIAL_DETAILS.quantity",
                    "GEN_MATERIAL_DETAILS.quantity_issued",
                    "SEC_USERS.name as originator",
                    "CONFIG_STATUSES.name as status_name",
                    "CONFIG_REQUISITION_TYPES.name as requisition_type")
                ->orderBy("GEN_MATERIAL_HEADERS.created_at", "desc")
                ->get();
        } else {
            return DB::            table("GEN_MATERIAL_HEADERS as mat_head")
//                ->addSelect()
                ->leftJoin("GEN_MATERIAL_DETAILS",
                    "mat_head.req_no",
                    QueryComparisonOperator::EQUALS,
                    "GEN_MATERIAL_DETAILS.req_no")
                ->leftJoin("CONFIG_STATUSES",
                    "mat_head.status",
                    QueryComparisonOperator::EQUALS,
                    "CONFIG_STATUSES.code")
                ->leftJoin("CONFIG_REQUISITION_TYPES",
                    "mat_head.requisition_type",
                    QueryComparisonOperator::EQUALS,
                    "CONFIG_REQUISITION_TYPES.code")
                ->leftJoin("SEC_USERS",
                    "mat_head.requested_by",
                    QueryComparisonOperator::EQUALS,
                    "SEC_USERS.staff_no")
                ->where("CONFIG_STATUSES.MODULE",
                    QueryComparisonOperator::EQUALS,
                    Modules::MATERIAL->value)
                ->where("mat_head.IS_FUEL",
                    QueryComparisonOperator::EQUALS,
                    "Y")
                ->select(
                    "mat_head.*",
                    "GEN_MATERIAL_DETAILS.quantity",
                    "GEN_MATERIAL_DETAILS.quantity_issued",
                    "SEC_USERS.name as originator",
                    "CONFIG_STATUSES.name as status_name",
                    "CONFIG_REQUISITION_TYPES.name as requisition_type")
                ->orderBy("mat_head.created_at", "desc")
                ->when($search, function (Builder $query) use ($search) {
                    $query->where('mat_head.requested_by', $search);
                    $query->orWhere('mat_head.veh_reg_no', $search);
                    $query->orWhere('mat_head.st_pur', $search);

                })
                ->when(!$search, function ($query){
                    $query->whereDate("valid_date_from", '>=', now()->subDays(7));
                })
                //                ->where('srn', '<=', 100)
//                ->limit(100)
                ->get();
        }

    }

    public function updateStatus(mixed $reference, string $status): void {
        DB::beginTransaction();
        MaterialHeader::where("req_no", $reference)
            ->update(["status" => $status]);
        DB::commit();
    }

    /**
     * @throws WorkflowTaskCreationFailedException
     * @throws FuelRequisitionException
     */
    public function processRequisitionUpdate(FuelRequisitionUpdate $request): JsonResponse {
        $requisitionReferenceNumber = $request->get('reference');
        $remarks = $request->get('Comments');
        $submittedAction = $request->get('Approved');
        $justification = $request->get('justification');
        $materialQuantity = $request->get('material_quantity');

        Log::info("Resubmission Action " . $submittedAction);

        Log::debug("Update Here $requisitionReferenceNumber");

        DB::beginTransaction();

        if ($submittedAction == WorkflowActions::RESUBMIT) {
            MaterialHeader::where("req_no", $requisitionReferenceNumber)
                ->update(["comments" => $justification,]);

            MaterialDetail::where("req_no", $requisitionReferenceNumber)
                ->update(["quantity" => $materialQuantity]);
        }

        $this->processFuelRequisitionWorkflow(
            $requisitionReferenceNumber,
            $submittedAction,
            $remarks,
            $justification
        );

        DB::commit();

        return response()->json([
            "success" => true,
            "message" => "Requisition Resubmitted For Approval.",
            "redirectUrl" => URL::signedRoute("show.fuel.requisition",
                ["ref" => $requisitionReferenceNumber]
            )
        ]);
    }

    /**
     * @throws NoOdometerEntryException
     */
    private function getLatestOdometerLogsEntry(mixed $registrationNumber) {
        $odometerLog = DB::table('vm_fleet_movement_header')
            ->where('reg_no',
                QueryComparisonOperator::EQUALS,
                $registrationNumber)
            ->select(DB::raw('MAX(odometer_end) as max_odometer'))
            ->first();

        if (empty($odometerLog)) {
            throw new NoOdometerEntryException(
                str_replace(
                    "@reg",
                    $registrationNumber,
                    ErrorMessages::getMessage("err_0034")
                )
            );
        }

        return $odometerLog->max_odometer;
    }

    private function getOdometerOnLastIssue(mixed $registrationNumber) {
        return DB::table('gen_material_headers')
                ->where('veh_reg_no',
                    QueryComparisonOperator::EQUALS,
                    $registrationNumber)
                ->where("is_fuel",
                    QueryComparisonOperator::EQUALS,
                    "Y")
                ->whereIn('status', [
                    StatusHelper::partiallyReleased(),
                    StatusHelper::fullyReleased(),
                    StatusHelper::partiallyReleasedExpired(),
                    StatusHelper::partiallyReleasedCancelled(),
                    StatusHelper::expired()
                ])
                ->select(DB::raw('MAX(odometer) as odometer'))
                ->first()->odometer ?? 0;
    }

    private function getVehicleFuelConsumptionData(mixed $vehicleReference): array {
        Log::debug("Registration Number $vehicleReference");

        $consumptionData = DB::table('vm_vehicle_header vh')
            ->join(
                'vm_engine_details ed',
                'vh.id',
                QueryComparisonOperator::EQUALS,
                'ed.vehicle_header_id'
            )
            ->where(
                'vh.registration_number',
                QueryComparisonOperator::EQUALS,
                $vehicleReference
            )
            ->select('ed.fuel_consumption', 'ed.tank_capacity')
            ->first();

        if (empty($consumptionData)) {
            return ['fuel_consumption' => 0, 'tank_capacity' => 0];
        }

        Log::debug("Consumption $consumptionData->fuel_consumption");
        Log::debug("Tank Capacity $consumptionData->tank_capacity");

        return [
            $consumptionData->fuel_consumption ?? 0,
            $consumptionData->tank_capacity ?? 0
        ];
    }

    /**
     * @param FuelRequisitionPostRequest $requisitionPostRequest
     * @param mixed $registrationNumber
     * @param  $validFrom
     * @param  $validTo
     * @return string
     * @throws WorkflowTaskCreationFailedException
     */
    private function saveFuelRequisition(FuelRequisitionPostRequest $requisitionPostRequest,
                                         mixed                      $registrationNumber,
                                                                    $validFrom,
                                                                    $validTo): string {

        Log::debug("Registration Number   $registrationNumber");
        Log::debug("Validity Period From   $validFrom");
        Log::debug("Validity Period To     $validTo");
        /******************************************* Save Data **************************************/
        $user = Auth()->user();

        $requisition_reference_number = DocumentNumberGenerationService::generateReferenceNumber(
            WorkflowModules::FUEL_REQUISITION
        );
        $form_order_number = DocumentNumberGenerationService::generateReferenceNumber(
            WorkflowModules::STOCK_REQUISITION
        );

        $workflowProcess = "";
        $description = "";

        Log::debug("Requisition Type " . $requisitionPostRequest->get(self::REQUISITION_TYPE));

        $townFrom = null;
        $townTo = null;
        $requisitionType = $requisitionPostRequest->get(self::REQUISITION_TYPE);
        if ($requisitionType == RequisitionTypes::OutOfTown->value) {
            $workflowProcess = WorkflowProcessCodes::OutOfTownFuelRequisition->value;
            $description = "Out Of Town ";
            $townFrom = $requisitionPostRequest->get("departureTown") ?? '';
            $townTo = $requisitionPostRequest->get("destinationTown") ?? '';
        } elseif ($requisitionType == RequisitionTypes::Normal->value) {
            $workflowProcess = WorkflowProcessCodes::LocalFuelRequisition->value;
            $description = "Normal ";
        } elseif ($requisitionType == RequisitionTypes::Override->value) {
            $workflowProcess = WorkflowProcessCodes::OverrideFuelRequisition->value;
            $description = "Override ";
        }

        DB::beginTransaction();
        $short_description = $description
            . "Fuel Requisition For Vehicle Reg No. "
            . $registrationNumber;

        $long_description = $description
            . "Fuel Requisition Ref.No. "
            . $requisition_reference_number
            . " For Vehicle Reg No. "
            . $registrationNumber;

        $this->workflowService->initiateWorkflowProcess(
            $requisition_reference_number,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $requisitionPostRequest->get("justification"),
            $requisitionPostRequest->get("material_amount"),
            array(
                $short_description,
                $long_description
            ),
        );

        Log::debug("Workflow Initiated");

        $costBearer = $requisitionPostRequest->get("CostAssignedTo")
        == "CostCenterBasedRequisition" ?
            CostAssignment::COST_CENTER :
            CostAssignment::PROJECT;

        $matHeader = MaterialHeader::create(
            [
                "is_fuel" => "Y",
                "req_no" => $requisition_reference_number,
                "form_order" => $form_order_number,
                "status" => StatusHelper::new(),
                "veh_reg_no" => $registrationNumber,
                "cost_centre" => $requisitionPostRequest->get("cost_centre_code"),
                "valid_date_from" => $validFrom,
                "valid_date_to" => $validTo,
                "odometer" => $requisitionPostRequest->get("odometer_reading"),
                "town_from" => $townFrom,
                "town_to" => $townTo,
                "date_created" => Carbon::now(),
                "created_by" => $user->id,
                "project_name" => $requisitionPostRequest->get('ProjectName') ?? null,
                "requested_by" => $user->staff_no,
                "comments" => $requisitionPostRequest->get("justification"),
                self::REQUISITION_TYPE => $requisitionPostRequest->get(self::REQUISITION_TYPE),
                "cost_assigned_to" => $costBearer
            ]
        );

        $projectCode = $requisitionPostRequest->get("project_code")
            ?? $requisitionPostRequest->get("projectCode");

        MaterialDetail::create([
            "created_by" => $user->staff_no,
            "date_created" => Carbon::now(),
            "req_no" => $requisition_reference_number,
            "material_code" => $requisitionPostRequest->get("material_article_code"),
            "quantity" => $requisitionPostRequest->get("material_quantity"),
            "unit_of_measure" => $requisitionPostRequest->get("unit_of_measure"),
            "specifications" => $requisitionPostRequest->get("material_description"),
            "description" => $requisitionPostRequest->get("material_description"),
            "project_code" => $projectCode,
            "cost_centre" => $requisitionPostRequest->get("cost_centre_code"),
            "cost_centre_name" => $requisitionPostRequest->get("cost_center_name"),
            "reg_no" => $requisitionPostRequest->get("vehicle_registration"),
            "amount" => $requisitionPostRequest->get("material_amount"),
            "price" => $requisitionPostRequest->get("material_price"),
            "max_allowed" => $requisitionPostRequest->get("fuel_allocation")
        ]);

        $files = $requisitionPostRequest->allFiles();
        if (!empty($files)) {
            FileUploadService::uploadFile(
                $requisitionPostRequest,
                'authorityToTravel',
                'Attachments',
                $requisition_reference_number,
                'AuthorityToTravel',
                'AuthorityToTravel',
                $user
            );
        }

        DB::commit();

        RequisitionRaised::dispatch($matHeader, "fuel_requisition");
        Log::debug("Fuel Requisition " . $requisition_reference_number . " raised successfully");
        return $requisition_reference_number;
    }

    /**
     * @param mixed $registrationNumber
     * @return array
     */
    private static function getFuelLastIssue(mixed $registrationNumber): array {

           $result = DB::table('gen_material_headers h')
               ->where('veh_reg_no',
                   QueryComparisonOperator::EQUALS,
                   $registrationNumber)
               ->where('is_fuel',
                   QueryComparisonOperator::EQUALS,
                   'Y'
               )
               ->whereNotIn('status', ['45', '03', '01', '02'])
//                   ->whereRaw(DB::raw("status NOT IN('45','03','01','')"))
               ->select(DB::raw('MAX(created_at) as max_date'))
               ->first();


        $latestIssues = DB::table('gen_material_headers h')
            ->leftJoin(
                "gen_material_details d",
                "h.req_no",
                QueryComparisonOperator::EQUALS,
                "d.req_no")
            ->where(
                "veh_reg_no",
                QueryComparisonOperator::EQUALS,
                $registrationNumber
            )
            ->where('h.created_at',
                QueryComparisonOperator::EQUALS,
                $result->max_date
            )
            ->select(
                'h.st_pur',
                "h.req_no",
                "h.form_order",
                "h.created_at",
                "h.odometer",
                'h.valid_date_to'
            )->get();

        $latestIssue = $latestIssues->first();
        if (empty($latestIssue)) {
            return [0, null];
        }

        $quantityLastIssued = DB::table('fuel_management')
            ->where("voucher_no",
                QueryComparisonOperator::EQUALS,
                $latestIssue->form_order)
            ->select(DB::raw("SUM(quantity) as quantity"))
            ->groupBy('voucher_no')
            ->first();

        return [$quantityLastIssued->quantity ?? 0, $latestIssue];
    }

    private static function getLatestActiveRequisition(mixed $registrationNumber): mixed {
        return MaterialHeader::where("veh_reg_no", $registrationNumber)
            ->whereNotIn("status", [
                StatusHelper::cancelled(),
                StatusHelper::rejected(),
            ])
            ->orderBy("created_at", "desc")
            ->first();
    }


    /**
     * @param $reference
     * @param $submittedAction
     * @param string $remarks
     * @param string|null $subject
     * @return string
     * @throws FuelRequisitionException
     * @throws WorkflowTaskCreationFailedException
     */
    public function processFuelRequisitionWorkflow(
        $reference,
        $submittedAction,
        string $remarks,
        string $subject = null): string {
        $requisitionDetail = $this->getRequisitionDetail($reference);

        $process_code = $this->getProcessCode($requisitionDetail->requisition_type);

        list($action, $actionTaken, $message) = $this->getActionTaken($submittedAction);

        DB::beginTransaction();
        list($nextStepId, $nextUser) = $this->workflowService->invokeWorkFlow(
            $reference,
            $process_code,
            $action,
            $actionTaken,
            $remarks,
            $subject
        );

        $requisitionNumber = null;
        if ($nextStepId == 100) {
            list($requisitionNumber, $message) = $this->processApproval(
                $action,
                $reference,
                $message
            );
        } else {
            $status = '';
            if (strtolower($submittedAction) == WorkflowActions::APPROVE) {
                $status = StatusHelper::partiallyAuthorised();
                $message = self::APPROVED . $nextUser;
            } elseif ($action == WorkflowActions::sendBack()) {
                $status = TaskStatus::sentBack();
                $message = 'Request Returned to Originator';
            } elseif ($action == WorkflowActions::resubmit()) {
                $status = TaskStatus::submitted();
            }

            $this->updateStatus($reference, $status);
        }

        DB::commit();

        if ($nextStepId == 100) {
            FuelRequisitionWorkflowUpdate::dispatch(
                $reference,
                Auth::user(),
                ApprovalStage::full->value,
                $requisitionNumber
            );
        } else {
            if ($action == WorkflowActions::resubmit()) {
                RequisitionResubmitted::dispatch(
                    $reference,
                    $remarks,
                    Auth::user(),
                    ApprovalStage::resubmit->value,
                    null
                );
            } else {

                if ($action == WorkflowActions::sendBack()) {
                    $stage = ApprovalStage::sendBack->value;
                } else {
                    $stage = ApprovalStage::partial->value;
                }

                FuelRequisitionWorkflowUpdate::dispatch(
                    $reference,
                    Auth::user(),
                    $stage,
                    null
                );
            }
        }
        return $message;
    }

    /**
     * @param mixed $requisitionType
     * @return string
     */
    private function getProcessCode(mixed $requisitionType): string {
        $process_code = '';
        if ($requisitionType == RequisitionTypes::OutOfTown->value) {
            $process_code = WorkflowProcessCodes::OutOfTownFuelRequisition->value;
        } elseif ($requisitionType == RequisitionTypes::Normal->value) {
            $process_code = WorkflowProcessCodes::LocalFuelRequisition->value;
        } elseif ($requisitionType == RequisitionTypes::Override->value) {
            $process_code = WorkflowProcessCodes::OverrideFuelRequisition->value;
        }
        return $process_code;
    }

    /**
     * @param $submittedAction
     * @return array
     */
    private function getActionTaken($submittedAction): array {
        $action = 0;
        $actionTaken = '';
        $message = '';

        if ($submittedAction === WorkflowActions::APPROVE) {
            $action = WorkflowActions::approve();
            $actionTaken = "Approved";
            $message = 'Request Approved Successfully';
        } elseif ($submittedAction === WorkflowActions::REJECT) {
            $action = WorkflowActions::reject();
            $actionTaken = "Rejected";
            $message = 'Request Rejected';
        } elseif ($submittedAction === WorkflowActions::SEND_BACK) {
            $action = WorkflowActions::sendBack();
            $actionTaken = "SendBack";
            $message = 'Request Sent Back To Originator';
        } elseif ($submittedAction === WorkflowActions::RESUBMIT) {
            $action = WorkflowActions::resubmit();
            $actionTaken = "Resubmit";
            $message = 'Task Resubmitted To Previous Authority For Approval';
        } elseif ($submittedAction === WorkflowActions::CANCEL) {
            $action = WorkflowActions::cancel();
            $actionTaken = "Cancellation";
            $message = 'Task Cancelled';
        }
        return array($action, $actionTaken, $message);
    }

    private function updateRequisition($reference, string $status, string $requisition): void {
        DB::beginTransaction();
        MaterialHeader::where("req_no", $reference)
            ->update([
                "status" => $status,
                "st_pur" => $requisition,
                "proc_ref" => $requisition
            ]);
        DB::commit();
    }

    /**
     * @param mixed $action
     * @param $reference
     * @param mixed $message
     * @return array
     * @throws FuelRequisitionException
     */
    public function processApproval(mixed $action, $reference, mixed $message): array {
        $requisitionNumber = '';
        if ($action == WorkflowActions::approve()) {

            $requisitionNumber = $this->createStoresRequisition(
                $reference
            );

            $message = $message . ' Stores Requisition No.: ' . $requisitionNumber;
            $this->updateRequisition($reference, StatusHelper::authorised(), $requisitionNumber);
        } elseif ($action == WorkflowActions::reject()) {
            $status = StatusHelper::rejected();
            $message = 'Request Rejected.';
            $this->updateStatus($reference, $status);
        }
        return array($requisitionNumber, $message);
    }

    /**
     * @throws OrganisationUnitStateException
     */
    private function verifyUserUnitState($codeUnit): void {

        $unitCount = OrganizationalUnit::where(
            'code_unit',
            QueryComparisonOperator::EQUALS,
            $codeUnit
        )
            ->where('status',
                QueryComparisonOperator::EQUALS,
                StatusHelper::organizationStructureActive()
            )
            ->count();

        if ($unitCount == 0) {
            throw  new  OrganisationUnitStateException(
                ErrorMessages::getMessage('err_0039')
            );
        }

    }

}
