<?php

namespace App\Services\Requisitions;

use App\Constants\Accounts;
use App\Constants\ErrorMessages;
use App\Constants\TransactionType;
use App\Constants\WorkflowActions;
use App\Constants\WorkflowModules;
use App\Enums\Modules;
use App\Enums\RequisitionTypes;
use App\Enums\WorkflowProcessCodes;
use App\Events\RequisitionRaised;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Requests\FuelRequisitionPostRequest;
use App\Models\Common\BusinessUnit;
use App\Models\Common\CostCenter;
use App\Models\Common\OrganizationalUnit;
use App\Models\MaterialDetail;
use App\Models\MaterialHeader;
use App\Models\Security\User;
use App\Models\VehicleManagement\VehicleHeader;
use App\Services\FileUploads\FileUploadService;
use App\Services\Integration\ProcurementSystemIntegrationService;
use App\Services\VehicleManagement\VehicleDetailsService;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\Workflow\WorkflowService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class FuelRequisitionService
{
    private VehicleDetailsService $vehicleDetailsService;
    private WorkflowService $workflowService;
    private ProcurementSystemIntegrationService $procurementService;

    public function __construct(VehicleDetailsService               $vehicleDetailsService,
                                WorkflowService                     $workflowService,
                                ProcurementSystemIntegrationService $procurementService)
    {
        $this->vehicleDetailsService = $vehicleDetailsService;
        $this->workflowService = $workflowService;
        $this->procurementService = $procurementService;
    }

    /**
     * @param mixed $registrationNumber
     * @return mixed
     */
    private static function getFuelLastIssue(mixed $registrationNumber): mixed
    {
        //
        $result = DB::table('gen_material_headers h')
            ->where('veh_reg_no', '=', $registrationNumber)
            ->where('is_fuel', '=', 'Y')
            ->whereNotIn('status', ['45', '03', '01', '02'])
            ->select(DB::raw('MAX(created_at) as max_date'))
            ->first();

        /*SELECT  FROM gen_material_headers WHERE  = AND  =  AND  NOT IN ;*/

        $latestIssues = DB::table('gen_material_headers h')
            ->leftJoin(
                "gen_material_details d",
                "h.req_no",
                "=",
                "d.req_no")
            ->where(
                "veh_reg_no",
                "=",
                $registrationNumber
            )
            ->where('h.created_at',
                "=",
                $result->max_date
            )
            ->select(
                "h.req_no",
                "h.created_at",
                "h.odometer"
            )->get();

        //
        $latestIssue = $latestIssues->first();
        $quantityLastIssued = DB::table('gen_material_details')
            ->where("req_no", "=", $latestIssue->req_no)
            ->select(DB::raw("SUM(quantity) as quantity"))
            ->groupBy('req_no')
            ->first();

        return $quantityLastIssued->quantity ?? 0; //, 'latestIssue' => $latestIssue];
    }

    private static function getLastIssuedRequisitionDetailsByRegNumber(mixed $registrationNumber): mixed
    {
        return MaterialHeader::where("veh_reg_no", $registrationNumber)
            ->whereNotIn("status", [StatusHelper::cancelled(), StatusHelper::rejected(), StatusHelper::new(), StatusHelper::authorised()])
            ->orderBy("created_at", "desc")
            ->first();
    }


    /**
     * @throws FuelRequisitionException|WorkflowTaskCreationFailedException
     */
    public function processRequest(FuelRequisitionPostRequest $requisitionPostRequest): JsonResponse
    {
        $isOutOfTownRequisition =
            $requisitionPostRequest->get("requisition_type") == RequisitionTypes::OutOfTown->value;

        $isLocalRequisition = $requisitionPostRequest->get("requisition_type") == RequisitionTypes::Normal->value;

        $isOverrideRequisition = $requisitionPostRequest->get("requisition_type") == RequisitionTypes::Override->value;

        $registrationNumber = $requisitionPostRequest->get("vehicle_registration");

        $vehicle = $this->verifyVehicleStatusAndFetchData($registrationNumber);

        // check if odometer was reset SELECT
        //    COUNT(*)
        //FROM
        //    gtaodores
        //WHERE
        //        reg_no = 'AAT 6303'
        //    AND status = '10';


        $latestOdometerLogs = $this->getLatestOdometerLogsEntry($registrationNumber);

        Log::info("Latest Mileage Return $latestOdometerLogs");

        $quantityLastIssued = $this->getFuelLastIssue($registrationNumber);

        //$latestIssue]
        Log::info("Latest Issued Amount $quantityLastIssued");

        $odometerOnLastIssue = $this->getOdometerOnLastIssue($registrationNumber);

        $this->checkVehicleAssignedUserUnitAndBuCcStatus($registrationNumber);

        [$fuel_consumption, $tank_capacity] = $this->getVehicleFuelConsumptionData($registrationNumber);

        // check that current user provided odometer is greater than last issue
        $userProvidedOdometer = $requisitionPostRequest->get('odometer_reading');

        $this->validateOdometerAgainstLastIssue(
            $odometerOnLastIssue,
            $userProvidedOdometer,
            $odometerOnLastIssue
        );


        // check that current user provided odometer is greater than last issue
        $this->validateCurrentOdometerAgainstMileageReturn(
            $latestOdometerLogs,
            $userProvidedOdometer,
        );

        Log::info("Calculating Maximum Distance that should have been covered by  $registrationNumber");
        Log::debug('Consumption ' . $fuel_consumption);
        Log::debug('Quantity Last Issued ' . $quantityLastIssued);
        $maximumDistance = ($quantityLastIssued * ($fuel_consumption ?? $vehicle->fuel_consumption));

        Log::debug("Maximum Distance " . $maximumDistance);
        Log::debug("Odometer Last Issue " . $odometerOnLastIssue);
        $newEstimatedOdometer = $maximumDistance + $odometerOnLastIssue;
        Log::debug("Last Issue + Maximum Distance " . $newEstimatedOdometer);

        // check the value of deviation 5 - 8 = -3
        $variance = (float)$userProvidedOdometer - $newEstimatedOdometer;
        Log::debug("Odometer Variance " . $variance);

        if ($variance < 0) {
            throw new FuelRequisitionException("The Odometer Value is too low compared to Last Issue");
        } elseif ($variance > 200) {
            throw new FuelRequisitionException("The Odometer Value is too High compared to Last Issue");
        }

        $latestPreviousRequisition = self::getLastIssuedRequisitionDetailsByRegNumber($registrationNumber);

        DB::beginTransaction();

        // pick last requisition if any
        $openRequisitionStatusList = [
            StatusHelper::new(),
            StatusHelper::partiallyReleased(),
            StatusHelper::authorised(),
            StatusHelper::partiallyAuthorised()
        ];

        if (!empty($latestPreviousRequisition)) {
            Log::info("Status of Previous Requisition for
                $registrationNumber has $latestPreviousRequisition->status status");
        } else {
            Log::info("Status of Previous Requisition for $registrationNumber . Is Empty");
        }

        $valid_to = Carbon::createFromFormat("d/m/Y", $requisitionPostRequest->get("next_fuel_date"));
        $valid_from = Carbon::createFromFormat("d/m/Y", $requisitionPostRequest->get("request_date"));

        if ($isLocalRequisition) {
            // quantity requested can not be more than allocated
            if ($requisitionPostRequest->get("material_quantity") > $requisitionPostRequest->get("fuel_allocation")) {
                return response()->json([
                    "success" => false,
                    "message" => "Quantity requested can not be more than allocation"
                ]);
            }
            if (!empty($latestPreviousRequisition)) {
                if (in_array($latestPreviousRequisition->status, $openRequisitionStatusList)) {
                    // requisition is open/pending

                    if (RequisitionTypes::Normal->value == $latestPreviousRequisition->requisition_type
                        || RequisitionTypes::Override->value == $latestPreviousRequisition->requisition_type) {

                        return response()->json([
                            "success" => false,
                            "message" =>
                                str_replace("@veh_reg", $registrationNumber,
                                    str_replace("@date_valid_to",
                                        Carbon::parse($latestPreviousRequisition->valid_date_to)->format("d/m/Y"),
                                        str_replace("@req_no",
                                            $latestPreviousRequisition->st_pur ?? $latestPreviousRequisition->req_no,
                                            ErrorMessages::getMessage("err_0001")
                                        )
                                    )
                                ),
                        ]);
                    }

                    // cancel out of town
                    if ($latestPreviousRequisition->requisition_type == RequisitionTypes::OutOfTown->value) {
                        // cancel requisition
                        $latestPreviousRequisition->status = StatusHelper::cancelled();
                        $latestPreviousRequisition->save();

                        $this->procurementService->cancelStoresRequisition($latestPreviousRequisition->st_pur);

                        //cancel associated task
                        $this->cancelAssociatedTask($latestPreviousRequisition);
                    }

                } else {

                    // fully issued
                    if (RequisitionTypes::Normal->value == $latestPreviousRequisition->requisition_type
                        || RequisitionTypes::Override->value == $latestPreviousRequisition->requisition_type
                    ) {
                        $this->checkIfPreviousRequisitionPeriodElapsed($latestPreviousRequisition, $valid_from, $registrationNumber);
                    }
                }
            }
        } elseif ($isOutOfTownRequisition) {

            // out of town requisition can be more than allocated
            $valid_from = Carbon::createFromFormat("Y-m-d", $requisitionPostRequest->get("departure_date"));
            $valid_to = Carbon::createFromFormat("Y-m-d", $requisitionPostRequest->get("return_date"));

            if (!empty($latestPreviousRequisition)) {
                if (in_array($latestPreviousRequisition->status, $openRequisitionStatusList)) {
                    // cancel requisition
                    $latestPreviousRequisition->status = StatusHelper::cancelled();
                    $latestPreviousRequisition->save();

                    $this->procurementService->cancelStoresRequisition($latestPreviousRequisition->st_pur);

                    //cancel associated task
                    $this->cancelAssociatedTask($latestPreviousRequisition);
                }
            }

        } elseif ($isOverrideRequisition) {
            // if there is no previous requisition, throw error
            if (empty($latestPreviousRequisition)) {
                throw new FuelRequisitionException(ErrorMessages::getMessage("err_0008"));
            }

            if (in_array($latestPreviousRequisition->status, $openRequisitionStatusList)) {
                $message = "";

                if (RequisitionTypes::Override->value == $latestPreviousRequisition->requisition_type) {
                    $message = ErrorMessages::getMessage("err_0006");
                }

                if (RequisitionTypes::Normal->value == $latestPreviousRequisition->requisition_type) {
                    $message = ErrorMessages::getMessage("err_0007");
                }

                if (RequisitionTypes::OutOfTown->value == $latestPreviousRequisition->requisition_type) {
                    $message = ErrorMessages::getMessage("err_0014");
                }

                return response()->json([
                    "success" => false,
                    "message" =>
                        str_replace("@veh_reg", $registrationNumber,
                            str_replace("@date_valid_to",
                                Carbon::parse($latestPreviousRequisition->valid_date_to)->format("d/m/Y"),
                                str_replace("@req_no",
                                    $latestPreviousRequisition->st_pur ?? $latestPreviousRequisition->req_no,
                                    $message)
                            )
                        ),
                ]);
            }

            // if latest previous is override or out of town, fail
            if (RequisitionTypes::Override->value == $latestPreviousRequisition->requisition_type
                || RequisitionTypes::OutOfTown->value == $latestPreviousRequisition->requisition_type
            ) {
                return response()->json([
                    "success" => false,
                    "message" =>
                        str_replace("@veh_reg", $registrationNumber,
                            str_replace("@date_valid_to",
                                Carbon::parse($latestPreviousRequisition->valid_date_to)->format("d/m/Y"),
                                str_replace("@req_no",
                                    $latestPreviousRequisition->st_pur ?? $latestPreviousRequisition->req_no,
                                    ErrorMessages::getMessage("err_0006"))
                            )
                        ),
                ]);
            }

            // check if your request date is before the end of previous requisition,
            // override has to be before expiry of previous requisition
            if (
                RequisitionTypes::Normal->value == $latestPreviousRequisition->requisition_type
                && $valid_from->greaterThan(Carbon::parse($latestPreviousRequisition->valid_date_to))
            ) {
                return response()->json([
                    "success" => false,
                    "message" =>
                        str_replace("@veh_reg", $registrationNumber,
                            str_replace("@date_valid_to",
                                Carbon::parse($latestPreviousRequisition->valid_date_to->valid_date_to)->format("d/m/Y"),
                                str_replace("@req_no",
                                    $latestPreviousRequisition->st_pur ?? $latestPreviousRequisition->req_no,
                                    ErrorMessages::getMessage("err_0015"))
                            )
                        ),
                ]);
            }

            // quantity requested can not be more than allocated
            if ($requisitionPostRequest->get("fuel_allocation") < $requisitionPostRequest->get("material_quantity")) {
                return response()->json([
                    "success" => false,
                    "message" => "Quantity requested can not be more than allocation"
                ]);
            }
        }

        Log::info("Vehicle Reg Is $registrationNumber");
        /******************************************* Save Data **************************************/
        $user = Auth()->user();

        $requisition_reference_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::FUEL_REQUISITION);
        $form_order_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::STOCK_REQUISITION);

        $workflowProcess = "";
        $description = "";

        Log::info("Requisition Type " . $requisitionPostRequest->get("requisition_type"));

        $townFrom = null;
        $townTo = null;
        if ($requisitionPostRequest->get("requisition_type") == RequisitionTypes::OutOfTown->value) {
            $workflowProcess = WorkflowProcessCodes::OutOfTownFuelRequisition->value;
            $description = "Out Of Town ";
            $townFrom = $requisitionPostRequest->has("departureTown") ? $requisitionPostRequest->get("departureTown") : null;
            $townTo = $requisitionPostRequest->has("destinationTown") ? $requisitionPostRequest->get("destinationTown") : null;
        } elseif ($requisitionPostRequest->get("requisition_type") == RequisitionTypes::Normal->value) {
            $workflowProcess = WorkflowProcessCodes::NormalFuelRequisition->value;
            $description = "Normal ";
        } elseif ($requisitionPostRequest->get("requisition_type") == RequisitionTypes::Override->value) {
            $workflowProcess = WorkflowProcessCodes::OverrideFuelRequisition->value;
            $description = "Override ";
        }

        $short_description = $description . "Fuel Requisition For Vehicle Reg No. " . $registrationNumber;
        $long_description = $description . "Fuel Requisition Ref.No. " . $requisition_reference_number . " For Vehicle Reg No. " . $registrationNumber;

        $this->workflowService->initiateWorkflowProcess(
            $requisition_reference_number,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $requisitionPostRequest->get("justification"),
            $user,
            $requisitionPostRequest->get("material_amount"),
            $short_description,
            $long_description
        );

        $matHeader = MaterialHeader::create(
            [
                "is_fuel" => "Y",
                "req_no" => $requisition_reference_number,
                "form_order" => $form_order_number,
                "status" => StatusHelper::new(),
                "veh_reg_no" => $registrationNumber,
                "cost_centre" => $requisitionPostRequest->get("cost_centre_code"),
                "valid_date_from" => $valid_from,
                "valid_date_to" => $valid_to,
                "odometer" => $requisitionPostRequest->get("odometer_reading"),
                "town_from" => $townFrom,
                "town_to" => $townTo,
                "date_created" => Carbon::now(),
                "created_by" => $user->id,
                "project_name" => $requisitionPostRequest->get('ProjectName') ?? null,
                "requested_by" => $user->staff_no,
                "comments" => $requisitionPostRequest->get("justification"),
                "requisition_type" => $requisitionPostRequest->get("requisition_type"),
                "cost_assigned_to" => $requisitionPostRequest->get("CostAssignedTo") == "CostCenterBasedRequisition" ? "CostCenter" : "Project"
            ]
        );

        MaterialDetail::create([
            "created_by" => $user->staff_no,
            "date_created" => Carbon::now(),
            "req_no" => $requisition_reference_number,
            "material_code" => $requisitionPostRequest->get("material_article_code"),
            "quantity" => $requisitionPostRequest->get("material_quantity"),
            "unit_of_measure" => $requisitionPostRequest->get("unit_of_measure"),
            "specifications" => $requisitionPostRequest->get("material_description"),
            "description" => $requisitionPostRequest->get("material_description"),
            "project_code" => $requisitionPostRequest->get("project_code") ?? $requisitionPostRequest->get("projectCode"),
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

        // send notification
        RequisitionRaised::dispatch($matHeader, "fuel_requisition");
        Log::info("Requisition " . $requisition_reference_number . " raised successfully");

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
    public function verifyVehicleStatusAndFetchData($reference)
    {
        $allowedStatus = [StatusHelper::active()];

        $vehicle = VehicleHeader::where("registration_number", "=", $reference)->first();

        if (empty($vehicle) || !in_array($vehicle->status, $allowedStatus)) {
            throw new FuelRequisitionException(ErrorMessages::getMessage("err_0004"), 1000);
        }

        return $vehicle;
    }

    /**
     * @throws FuelRequisitionException
     */
    public function validateCurrentOdometerAgainstMileageReturn($latestOdometerLogEntry, $userProvidedOdometer): bool
    {
        if ($userProvidedOdometer <= $latestOdometerLogEntry) {
            throw new FuelRequisitionException(ErrorMessages::getMessage("err_0013"), 1000);
        }

        return true;
    }

    /**
     * @param $responsibleHeadStaffNumber
     * @return void
     * @throws FuelRequisitionException
     */
    public function verifyVehicleResponsibleUserIsActive($responsibleHeadStaffNumber): void
    {
        $responsibleHead = User::where("staff_no", "=", $responsibleHeadStaffNumber)->first();

        if (empty($responsibleHead) || $responsibleHead->con_st_code != StatusHelper::activeUser()) {
            throw new FuelRequisitionException(ErrorMessages::getMessage("err_0003"), 300);
        }
    }

    /**
     * Validates the odometer reading on request is greater than the previous issue
     * @param $previousRequisition
     * @param $userProvidedOdometerReading
     * @param $odometerOnLastIssue
     * @return void
     * @throws FuelRequisitionException
     */
    public function validateOdometerAgainstLastIssue($previousRequisition, $userProvidedOdometerReading, $odometerOnLastIssue): void
    {
        // verify that odometer reading is not the same as previous requisition
        if ($userProvidedOdometerReading <= $odometerOnLastIssue) {
            throw new FuelRequisitionException(
                str_replace("@veh_reg", $previousRequisition->veh_reg_no,
                    str_replace("@date_valid_to",
                        Carbon::parse($previousRequisition->valid_date_to)->format("d/m/Y"),
                        str_replace("@req_no",
                            $previousRequisition->st_pur ?? $previousRequisition->req_no,
                            ErrorMessages::getMessage("err_0017")))),
                1000);
        }
    }

    /**
     * @param $previousRequisition
     * @param bool|Carbon $valid_from
     * @param $reg_num
     * @return void
     * @throws FuelRequisitionException
     */
    public function checkIfPreviousRequisitionPeriodElapsed($previousRequisition, bool|Carbon $valid_from, $reg_num): void
    {
        // check if previous requisition period elapsed
        if ($valid_from->lessThanOrEqualTo(Carbon::parse($previousRequisition->valid_date_to))) {
            throw new FuelRequisitionException(
                str_replace("@veh_reg", $reg_num,
                    str_replace("@date_valid_to",
                        Carbon::parse($previousRequisition->valid_date_to)->format("d/m/Y"),
                        str_replace("@req_no",
                            $previousRequisition->st_pur ?? $previousRequisition->req_no,
                            ErrorMessages::getMessage("err_0002")))),
                999);
        }
    }

    /**
     * @param $req_no
     * @return Model|Builder|object|null
     */
    public function getRequisitionDetail($req_no): mixed
    {
        $results = DB::table("GEN_MATERIAL_HEADERS")
            ->where("GEN_MATERIAL_HEADERS.req_no", $req_no)
            ->join("GEN_MATERIAL_DETAILS", "GEN_MATERIAL_HEADERS.req_no", "=", "GEN_MATERIAL_DETAILS.req_no")
            ->leftJoin("CONFIG_STATUSES", "GEN_MATERIAL_HEADERS.status", "=", "CONFIG_STATUSES.code")
            ->leftJoin("SEC_USERS", "GEN_MATERIAL_HEADERS.requested_by", "=", "SEC_USERS.staff_no")
            ->where("CONFIG_STATUSES.MODULE", "=", "MAT")
            ->select("GEN_MATERIAL_HEADERS.*",
                "GEN_MATERIAL_DETAILS.*",
                'SEC_USERS.name as originator',
                'SEC_USERS.job_title',
                "CONFIG_STATUSES.name as status_name",
                "CONFIG_STATUSES.color_code")
            ->get();

        return $results->first();

    }

    /**
     * @throws FuelRequisitionException
     */
    public function createStoresRequisition(string $reference): string
    {
        $requisitionDetail = self::getRequisitionDetail($reference);

        if ($requisitionDetail->cost_assigned_to === 'CostCenter') {
            $results = $this->procurementService->createStoresRequisition(
                $reference,
                $requisitionDetail->veh_reg_no,
                $requisitionDetail->form_order,
                Accounts::MOTOR_VEHICLE_FUEL_LUBRICANTS_ACCOUNT,
                TransactionType::STORES_REQUISITIONS,
            );
        } else {
            $results = $this->procurementService->createStoresRequisition(
                $reference,
                $requisitionDetail->veh_reg_no,
                $requisitionDetail->form_order,
                Accounts::MOTOR_VEHICLE_PROJECTS_FUEL_ACCOUNT,
                TransactionType::STORES_REQUISITIONS,
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

        Log::info("Stores Requisition Generated with document " . $results);

        return $results;
    }

    public function getLatestRequisition($vehicle_registration)
    {
        $queryResult = DB::table("GEN_MATERIAL_HEADERS")
            ->leftJoin("CONFIG_STATUSES", "GEN_MATERIAL_HEADERS.status", "=", "CONFIG_STATUSES.code")
            ->leftJoin("CONFIG_REQUISITION_TYPES", "GEN_MATERIAL_HEADERS.requisition_type", "=", "CONFIG_REQUISITION_TYPES.code")
            ->where("GEN_MATERIAL_HEADERS.veh_reg_no", "=", $vehicle_registration)
            ->select("GEN_MATERIAL_HEADERS.*", "CONFIG_STATUSES.name as status_name", "CONFIG_REQUISITION_TYPES.name as requisition_type")
            ->orderBy("GEN_MATERIAL_HEADERS.created_at", "desc")
            ->get();

        return empty($queryResult) ? [] : $queryResult->first();
    }

    public function getMyRequisitions($staff_no): Collection
    {
        if ($staff_no) {
            return DB::table("GEN_MATERIAL_HEADERS")
                ->leftJoin("GEN_MATERIAL_DETAILS", "GEN_MATERIAL_HEADERS.req_no", "=", "GEN_MATERIAL_DETAILS.req_no")
                ->leftJoin("CONFIG_STATUSES", "GEN_MATERIAL_HEADERS.status", "=", "CONFIG_STATUSES.code")
                ->leftJoin("CONFIG_REQUISITION_TYPES", "GEN_MATERIAL_HEADERS.requisition_type", "=", "CONFIG_REQUISITION_TYPES.code")
                ->leftJoin("SEC_USERS", "GEN_MATERIAL_HEADERS.requested_by", "=", "SEC_USERS.staff_no")
                ->where("GEN_MATERIAL_HEADERS.requested_by", "=", $staff_no)
                ->where("CONFIG_STATUSES.MODULE", "=", Modules::Material)
                ->where("GEN_MATERIAL_HEADERS.IS_FUEL", "=", "Y")
                ->select(
                    "GEN_MATERIAL_HEADERS.*",
                    "GEN_MATERIAL_DETAILS.quantity",
                    "GEN_MATERIAL_DETAILS.quantity_issued",
                    "SEC_USERS.name as originator",
                    "CONFIG_STATUSES.name as status_name",
                    "CONFIG_REQUISITION_TYPES.name as requisition_type")
                ->orderBy("GEN_MATERIAL_HEADERS.created_at", "desc")
                ->get();
        } else {
            return DB::table("GEN_MATERIAL_HEADERS")
                ->leftJoin("GEN_MATERIAL_DETAILS", "GEN_MATERIAL_HEADERS.req_no", "=", "GEN_MATERIAL_DETAILS.req_no")
                ->leftJoin("CONFIG_STATUSES", "GEN_MATERIAL_HEADERS.status", "=", "CONFIG_STATUSES.code")
                ->leftJoin("CONFIG_REQUISITION_TYPES", "GEN_MATERIAL_HEADERS.requisition_type", "=", "CONFIG_REQUISITION_TYPES.code")
                ->leftJoin("SEC_USERS", "GEN_MATERIAL_HEADERS.requested_by", "=", "SEC_USERS.staff_no")
                ->where("CONFIG_STATUSES.MODULE", "=", Modules::Material)
                ->where("GEN_MATERIAL_HEADERS.IS_FUEL", "=", "Y")
                ->select(
                    "GEN_MATERIAL_HEADERS.*",
                    "GEN_MATERIAL_DETAILS.quantity",
                    "GEN_MATERIAL_DETAILS.quantity_issued",
                    "SEC_USERS.name as originator",
                    "CONFIG_STATUSES.name as status_name",
                    "CONFIG_REQUISITION_TYPES.name as requisition_type")
                ->orderBy("GEN_MATERIAL_HEADERS.created_at", "desc")
                ->get();
        }

    }

    /**
     * @param $latestPreviousRequisition
     * @return void
     */
    public function cancelAssociatedTask($latestPreviousRequisition): void
    {
        if (RequisitionTypes::Normal->value == $latestPreviousRequisition->requisition_type) {
            $this->workflowService->cancelProcessTask(
                $latestPreviousRequisition->req_no,
                WorkflowProcessCodes::NormalFuelRequisition->value);
        } elseif (RequisitionTypes::OutOfTown->value == $latestPreviousRequisition->requisition_type) {
            $this->workflowService->cancelProcessTask(
                $latestPreviousRequisition->req_no,
                WorkflowProcessCodes::OutOfTownFuelRequisition->value);
        } elseif (RequisitionTypes::Override->value == $latestPreviousRequisition->requisition_type) {
            $this->workflowService->cancelProcessTask(
                $latestPreviousRequisition->req_no,
                WorkflowProcessCodes::OverrideFuelRequisition->value);
        }
    }

    public function updateStatus(mixed $reference, string $status): void
    {
        DB::beginTransaction();
        MaterialHeader::where("req_no", $reference)
            ->update(["status" => $status]);
        DB::commit();
    }

    private function getLatestOdometerLogsEntry(mixed $registrationNumber)
    {
        return DB::table('vm_fleet_movement_header')
            ->where('reg_no', '=', $registrationNumber)
            ->select(DB::raw('MAX(odometer_end) as max_odometer'))
            ->first()->max_odometer;
    }

    private function getOdometerOnLastIssue(mixed $registrationNumber): object|null
    {
        return DB::table('gen_material_headers')
            ->where('veh_reg_no', '=', $registrationNumber)
            ->where("is_fuel", "=", "Y")
            ->whereIn('status', [
                StatusHelper::partiallyReleased(),
                '32',
                StatusHelper::partiallyReleasedExpired(),
                '46'])
            ->select(DB::raw('MAX(odometer)'))
            ->first();
    }

    /**
     * @param mixed $registrationNumber
     * @return void
     * @throws FuelRequisitionException
     */
    private function checkVehicleAssignedUserUnitAndBuCcStatus(mixed $registrationNumber): void
    {
        $assignmentInfo = DB::table('vm_vehicle_header vh')
            ->where("vh.registration_number", '=', $registrationNumber)
            ->leftJoin('vm_assignments as va',
                'vh.id',
                '=',
                "va.vehicle_header_id")
            ->select('va.business_unit',
                'va.cost_center',
                'vh.business_unit_code as user_unit',
                'va.directorate as zone',
                'va.business_area_code  as area',
                'va.responsible_head_id as responsible',
                'va.vehicleholder as supervisor')
            ->first();

        if (empty($assignmentInfo)) {
            return;
        }

        $countBu = BusinessUnit::where('code_bu', $assignmentInfo->business_unit)
            ->where("status", "=", StatusHelper::active())
            ->count();

        if ($countBu == 0) {
            throw new FuelRequisitionException("Business Unit Is Not Active");
        }


        $countCc = CostCenter::where('code_cost_center', $assignmentInfo->cost_center)
            ->where("status", "=", StatusHelper::active())
            ->count();

        if ($countCc == 0) {
            throw new FuelRequisitionException("Cost Center Is Not Active");
        }

        $countUserUnit = OrganizationalUnit::where('code_unit', $assignmentInfo->user_unit)
            ->where("status", "=", StatusHelper::organizationStructureActive())
            ->count();

        if ($countUserUnit == 0) {
            throw new FuelRequisitionException("User Unit Is Not Active");
        }

    }

    private function getVehicleFuelConsumptionData(mixed $vehicleReference): array
    {
        Log::info("Registration Number $vehicleReference");
        $consumptionData = DB::table('vm_vehicle_header vh')
            ->join(
                'vm_engine_details ed',
                'vh.id',
                '=',
                'ed.vehicle_header_id'
            )
            ->where(
                'vh.registration_number',
                '=',
                $vehicleReference
            )
            ->first();

        Log::info("Count $consumptionData->count()");

        if (empty($consumptionData)) {
            return ['fuel_consumption' => 0, 'tank_capacity' => 0];
        }

        return [
            'fuel_consumption' => $consumptionData->fuel_consumption ?? 0,
            'tank_capacity' => $consumptionData->tank_capacity ?? 0
        ];
    }
}
