<?php

namespace App\Services\Requisitions;

use App\Constants\Accounts;
use App\Constants\ErrorMessages;
use App\Constants\TransactionType;
use App\Enums\ItemTypes;
use App\Enums\RequisitionTypes;
use App\Enums\VehicleStatusEnum;
use App\Enums\WorkflowProcessCodes;
use App\Events\RequisitionRaised;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Requests\FuelRequisitionPostRequest;
use App\Models\MaterialDetail;
use App\Models\MaterialHeader;
use App\Models\Security\User;
use App\Models\vehiclemanagement\VehicleHeader;
use App\Models\Workflow\WorkflowActions;
use App\Models\Workflow\WorkflowModules;
use App\Services\Integration\ProcurementSystemIntegrationService;
use App\Services\VehicleManagement\VehicleDetailsService;
use App\Services\Workflow\ReferenceNumberGeneratorService;
use App\Services\Workflow\WorkflowService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
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
     * @throws FuelRequisitionException|WorkflowTaskCreationFailedException
     */
    public function processRequest(FuelRequisitionPostRequest $requisitionPostRequest): JsonResponse
    {
        $isOutOfTownRequisition =
            $requisitionPostRequest->get('requisition_type') == RequisitionTypes::OutOfTown->value;

        $isLocalRequisition = $requisitionPostRequest->get('requisition_type') == RequisitionTypes::Normal->value;

        $isOverrideRequisition = $requisitionPostRequest->get('requisition_type') == RequisitionTypes::Override->value;

        $registrationNumber = $requisitionPostRequest->get('vehicle_registration');

        $this->validateVehicleStatus($registrationNumber);

        //$this->validateVehicleResponsibleUserStatus($registrationNumber);

        // validate odometer reading
        self::validateCurrentOdometerAgainstInitial($registrationNumber, $requisitionPostRequest->get('odometer_reading'));

        DB::beginTransaction();

        // pick last requisition if any
        $openRequisitionStatusList = [StatusHelper::new(), StatusHelper::partiallyReleased(), StatusHelper::authorised(), StatusHelper::partiallyAuthorised(),];

        $latestPreviousRequisition = MaterialHeader::where('veh_reg_no', $registrationNumber)
            ->orderBy('date_created', 'desc')
            ->first();

        $valid_to = Carbon::createFromFormat('d/m/Y', $requisitionPostRequest->get('next_fuel_date'));
        $valid_from = Carbon::createFromFormat('d/m/Y', $requisitionPostRequest->get('request_date'));

        if ($isLocalRequisition) {
            if (!empty($latestPreviousRequisition)) {
                if (in_array($latestPreviousRequisition->status, $openRequisitionStatusList)) {
                    // requisition is open/pending

                    if (RequisitionTypes::Normal->value == $latestPreviousRequisition->requisition_type
                        || RequisitionTypes::Override->value == $latestPreviousRequisition->requisition_type) {

                        return response()->json([
                            'success' => false,
                            'message' => str_replace(
                                '@re_no',
                                $latestPreviousRequisition->req_no,
                                ErrorMessages::vehicleHasActiveRequisition())
                        ]);
                    } elseif ($latestPreviousRequisition->requisition_type == RequisitionTypes::OutOfTown->value) {
                        // cancel requisition
                        $latestPreviousRequisition->status = StatusHelper::cancelled();
                        $latestPreviousRequisition->save();

                        //cancel associated task
                        $this->workflowService->cancelProcessTask($latestPreviousRequisition->req_no);
                    }
                } else {

                    // fully issued
                    if (RequisitionTypes::Normal->value == $latestPreviousRequisition->requisition_type
                        || RequisitionTypes::Override->value == $latestPreviousRequisition->requisition_type
                    ) {
                        $this->checkIfPreviousRequisitionPeriodElapsed($latestPreviousRequisition, $valid_from);
                    }

                    // validate odometer against last issue
                    $this->validateOdometerAgainstLastIssue($latestPreviousRequisition, $requisitionPostRequest);

                }
            }

            // quantity requested can not be more than allocated
            if ($requisitionPostRequest->get('fuel_allocation') < $requisitionPostRequest->get('material_quantity')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantity requested can not be more than allocation'
                ]);
            }

        } elseif ($isOutOfTownRequisition) {
            // out of town requisition can be more than allocated
            $valid_from = Carbon::createFromFormat('Y-m-d', $requisitionPostRequest->get('departure_date'));
            $valid_to = Carbon::createFromFormat('Y-m-d', $requisitionPostRequest->get('return_date'));

            if (!empty($latestPreviousRequisition)) {
                if (in_array($latestPreviousRequisition->status, $openRequisitionStatusList)) {

                    // cancel requisition
                    $latestPreviousRequisition->status = StatusHelper::cancelled();
                    $latestPreviousRequisition->save();

                    //cancel associated task
                    $this->workflowService->cancelProcessTask($latestPreviousRequisition->req_no);
                } else {
                    // validate odometer against last issue
                    $this->validateOdometerAgainstLastIssue($latestPreviousRequisition, $requisitionPostRequest);
                }
            }

        } elseif ($isOverrideRequisition) {
            // if there is no previous requisition, throw error
            if (empty($latestPreviousRequisition)) {
                throw new FuelRequisitionException(ErrorMessages::overrideRequisitionWithoutPriorRequisition);
            }

            if (in_array($latestPreviousRequisition->status, $openRequisitionStatusList)) {
                // requisition is open/pending

                return response()->json([
                    'success' => false,
                    'message' => str_replace(
                        '@re_no',
                        $latestPreviousRequisition->req_no,
                        ErrorMessages::vehicleHasActiveRequisition())
                ]);
            }

            // quantity requested can not be more than allocated
            if ($requisitionPostRequest->get('fuel_allocation') < $requisitionPostRequest->get('material_quantity')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantity requested can not be more than allocation'
                ]);
            }

            // validate odometer against last issue
            $this->validateOdometerAgainstLastIssue($latestPreviousRequisition, $requisitionPostRequest);
        }

        //$maximumDistance = ($requisitionPostRequest->material_amount * $vehicle->fuel_consumption) + $requisitionPostRequest->odometer_reading;
        //Log::info($maximumDistance . ' distance is');

        Log::info($registrationNumber);
        /********************************************** Save Data **************************************/
        $user = Auth()->user();

        $requisition_reference_number = ReferenceNumberGeneratorService::generateReferenceNumber(WorkflowModules::FUEL_REQUISITION);

        $form_order_number = ReferenceNumberGeneratorService::generateReferenceNumber(WorkflowModules::STOCK_REQUISITION);

        $workflowProcess = '';
        Log::info('Requisition Type ' . $requisitionPostRequest->get('requisition_type'));
        if ($requisitionPostRequest->get('requisition_type') == RequisitionTypes::OutOfTown->value) {
            $workflowProcess = WorkflowProcessCodes::OutOfTownFuelRequisition->value;
        } elseif ($requisitionPostRequest->get('requisition_type') == RequisitionTypes::Normal->value) {
            $workflowProcess = WorkflowProcessCodes::NormalFuelRequisition->value;
        } elseif ($requisitionPostRequest->get('requisition_type') == RequisitionTypes::Override->value) {
            $workflowProcess = WorkflowProcessCodes::OverrideFuelRequisition->value;
        }

        $this->workflowService->initiateWorkflowProcess(
            $requisition_reference_number,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $requisitionPostRequest->get('justification'),
            $user,
            $requisitionPostRequest->material_amount
        );

        MaterialHeader::create(
            [
                'req_no' => $requisition_reference_number,
                'form_order' => $form_order_number,
                'status' => StatusHelper::new(),
                'item_type' => ItemTypes::StockItem,
                'veh_reg_no' => $registrationNumber,
                'cost_centre' => $requisitionPostRequest->get('cost_centre_code'),
                'valid_date_from' => $valid_from,
                'valid_date_to' => $valid_to,
                'odometer' => $requisitionPostRequest->get('odometer_reading'),
                'town_from' => $requisitionPostRequest->get('town_from'),
                'town_to' => $requisitionPostRequest->get('town_to'),
                'date_created' => Carbon::now(),
                'created_by' => $user->id,
                'requested_by' => $user->staff_no,
                'comments' => $requisitionPostRequest->justification,
                'requisition_type' => $requisitionPostRequest->requisition_type,
                'cost_assigned_to' => $requisitionPostRequest->CostAssignedTo == 'CostCenterBasedRequisition' ? 'CostCenter' : 'Project'
            ]
        );

        MaterialDetail::create([
            'created_by' => $user->staff_no,
            'date_created' => Carbon::now(),
            'req_no' => $requisition_reference_number,
            'material_code' => $requisitionPostRequest->material_article_code,
            'quantity' => $requisitionPostRequest->material_quantity,
            'unit_of_measure' => $requisitionPostRequest->unit_of_measure,
            'specifications' => $requisitionPostRequest->material_description,
            'project_code' => $requisitionPostRequest->project_code ?? $requisitionPostRequest->projectCode,
            //'project_name' => $request->project_code ?? $request->projectCode,
            //'supplier_code',
            'cost_centre' => $requisitionPostRequest->cost_centre_code,
            'cost_centre_name' => $requisitionPostRequest->cost_center_name,
            'reg_no' => $requisitionPostRequest->vehicle_registration,
            'amount' => $requisitionPostRequest->material_amount,
            'price' => $requisitionPostRequest->material_price,
            'max_allowed' => $requisitionPostRequest->fuel_allocation
        ]);

        DB::commit();

        //RequisitionRaised::dispatch();
        Log::info('Requisition ' . $requisition_reference_number . ' raised successfully');

        return response()->json([
            'success' => true,
            'message' => 'Requisition Submitted For Approval. Requisition Number ' . $requisition_reference_number,
            'redirectUrl' => URL::signedRoute('show.fuel.requisition', ['ref' => $requisition_reference_number])
        ]);
    }

    /**
     * @param $reference
     * @return void
     * @throws FuelRequisitionException
     */
    public function validateVehicleStatus($reference): void
    {
        $allowedStatus = [VehicleStatusEnum::active->value];

        $vehicle = VehicleHeader::where('registration_number', '=', $reference)->first();

        if (!in_array($vehicle->status, $allowedStatus)) {
            throw new FuelRequisitionException(ErrorMessages::vehicleNotActive, 0);
        }
    }

    /**
     * @throws FuelRequisitionException
     */
    public function validateCurrentOdometerAgainstInitial($registration_number, $currentOdometer): bool
    {
        $vehicleDetail = DB::table('VM_VEHICLE_HEADER')
            ->join('VM_CHASSIS_DETAILS',
                'VM_VEHICLE_HEADER.id',
                '=',
                'VM_CHASSIS_DETAILS.vehicle_header_id')
            ->where('VM_VEHICLE_HEADER.registration_number', trim($registration_number))
            ->select('VM_VEHICLE_HEADER.*', 'VM_CHASSIS_DETAILS.initial_odometer_reading')
            ->first();

        if ($vehicleDetail->initial_odometer_reading > $currentOdometer) {
            throw new FuelRequisitionException(ErrorMessages::invalidCurrentOdometerreading(), 0);
        }

        return true;
    }

    /**
     * @param $vehicleReference
     * @return void
     * @throws FuelRequisitionException
     */
    public function validateVehicleResponsibleUserStatus($vehicleReference): void
    {
        $vehicleDetail = DB::table('VM_VEHICLE_HEADER')
            ->join('VM_ASSIGNMENTS',
                'VM_VEHICLE_HEADER.id',
                '=',
                'VM_ASSIGNMENTS.vehicle_header_id')
            ->where('VM_VEHICLE_HEADER.registration_number', trim($vehicleReference))
            ->where('VM_ASSIGNMENTS.assignment_state', StatusHelper::active())
            ->select('VM_VEHICLE_HEADER.*', 'VM_ASSIGNMENTS.responsible_head_id')
            ->first();

        $responsibleHead = User::where('staff_no', '=', $vehicleDetail->responsible_head_id)->first();

        if (empty($responsibleHead) || $responsibleHead->con_st_code != StatusHelper::activeUser()) {
            throw new FuelRequisitionException(ErrorMessages::responsibleUserNotActive, 0);
        }
    }

    /**
     * Validates the odometer reading on request is greater than the previous issue
     * @param $previousRequisition
     * @param FuelRequisitionPostRequest $requisitionPostRequest
     * @return void
     * @throws FuelRequisitionException
     */
    public function validateOdometerAgainstLastIssue($previousRequisition, FuelRequisitionPostRequest $requisitionPostRequest): void
    {
        // verify that odometer reading is not the same as previous requisition
        if ($requisitionPostRequest->odometer_reading <= $previousRequisition->odometer) {
            throw new FuelRequisitionException(ErrorMessages::invalidCurrentOdometerReading(), 0);
        }
    }

    /**
     * @param $previousRequisition
     * @param bool|Carbon $valid_from
     * @return void
     * @throws FuelRequisitionException
     */
    public function checkIfPreviousRequisitionPeriodElapsed($previousRequisition, bool|Carbon $valid_from): void
    {
        // check if previous requisition period elapsed
        if (Carbon::parse($previousRequisition->valid_date_to)->lessThan($valid_from)) {
            throw new FuelRequisitionException(str_replace('@date_valid_to', $previousRequisition->valid_date_to,
                str_replace('@req_no', $previousRequisition->req_no, ErrorMessages::requisitionStillActive)), 0);
        }
    }

    /**
     * @param $req_no
     * @return Model|Builder|object|null
     */
    public function getRequisitionDetail($req_no)
    {
        $results = DB::table('GEN_MATERIAL_HEADERS')
            ->where('GEN_MATERIAL_HEADERS.req_no', $req_no)
            ->join('GEN_MATERIAL_DETAILS', 'GEN_MATERIAL_HEADERS.req_no', '=', 'GEN_MATERIAL_DETAILS.req_no')
            ->leftJoin('CONFIG_STATUSES', 'GEN_MATERIAL_HEADERS.status', '=', 'CONFIG_STATUSES.code')
            ->select('GEN_MATERIAL_HEADERS.*', 'GEN_MATERIAL_DETAILS.*', 'CONFIG_STATUSES.name as status_name', 'CONFIG_STATUSES.color_code')
            ->get();

        return $results->first();

    }

    /**
     * @throws FuelRequisitionException
     */
    public function processFuelRequisitionApproval(string $reference): void
    {
        $requisitionDetail = self::getRequisitionDetail($reference);

        $results = $this->procurementService->createStoresRequisition(
            $reference,
            $requisitionDetail->veh_reg_no,
            $requisitionDetail->form_order,
            Accounts::DefaultMotorVehicleAccount,
            TransactionType::FuelRequisition,
        );

        if (empty($results)) {
            throw new FuelRequisitionException("Requisition could not approved ");
        }

        if (!str_contains($results, 'J01')) {
            throw new FuelRequisitionException($results);
        }

        Log::info("Stores Requisition Generated with document" . $results);

    }
}
