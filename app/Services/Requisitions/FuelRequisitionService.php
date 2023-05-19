<?php

namespace App\Services\Requisitions;

use App\Constants\ErrorMessages;
use App\Enums\VehicleStatusEnum;
use App\Enums\WorkflowProcessCodes;
use App\Exceptions\FuelRequisitionException;
use App\Helpers\StatusHelper;
use App\Http\Requests\FuelRequisitionPostRequest;
use App\Models\MaterialDetail;
use App\Models\MaterialHeader;
use App\Models\Security\User;
use App\Models\vehiclemanagement\Assignment;
use App\Models\vehiclemanagement\ChassisDetail;
use App\Models\vehiclemanagement\VehicleHeader;
use App\Models\Workflow\WorkflowActions;
use App\Services\Integration\ProcurementService;
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

    const FUEL_REQUISITION_NUMBER_PREFIX = "ZFMFUE";
    private VehicleDetailsService $vehicleDetailsService;
    private WorkflowService $workflowService;
    private ProcurementService $procurementService;

    public function __construct(VehicleDetailsService $vehicleDetailsService,
                                WorkflowService       $workflowService,
                                ProcurementService    $procurementService)
    {
        $this->vehicleDetailsService = $vehicleDetailsService;
        $this->workflowService = $workflowService;
        $this->procurementService = $procurementService;
    }


    /**
     * @throws FuelRequisitionException
     */
    public function processRequest(FuelRequisitionPostRequest $requisitionPostRequest): JsonResponse
    {
        $registrationNumber = $requisitionPostRequest->get('vehicle_registration');

        $this->validateVehicleStatus($registrationNumber);

        //$this->validateVehicleResponsibleUserStatus($registrationNumber);

        if ($requisitionPostRequest->get('fuel_allocation') < $requisitionPostRequest->get('material_quantity')) {
            return response()->json([
                'success' => false,
                'message' => 'Quantity requested can not be more than allocation'
            ]);
        }

        self::validateCurrentOdometerAgainstInitial($registrationNumber, $requisitionPostRequest->get('odometer_reading'));

        $valid_to = null;
        $valid_from = null;

        if ($requisitionPostRequest->get('requisition_type') == '011') {
            $valid_to = Carbon::createFromFormat('Y-m-d', $requisitionPostRequest->get('return_date'));
            $valid_from = Carbon::createFromFormat('Y-m-d', $requisitionPostRequest->get('departure_date'));

        } else {
            $valid_to = Carbon::createFromFormat('d/m/Y', $requisitionPostRequest->get('next_fuel_date'));
            $valid_from = Carbon::createFromFormat('d/m/Y', $requisitionPostRequest->get('request_date'));
        }

        //$maximumDistance = ($requisitionPostRequest->material_amount * $vehicle->fuel_consumption) + $requisitionPostRequest->odometer_reading;

        //Log::info($maximumDistance . ' distance is');

        Log::info($registrationNumber);
        // pick last requisition
        $previousRequisition = MaterialHeader::where('reg_no', $registrationNumber)
            ->whereIn('status', [
                StatusHelper::new(),
                StatusHelper::approved(),
                StatusHelper::partiallyReleased()
            ])
            ->orderBy('date_created', 'desc')
            ->first();


        // if there is an open requisition
        $openRequisitionStatusList = [StatusHelper::new(), StatusHelper::partiallyReleased()];

        if (!empty($previousRequisition)) {
            if (in_array($previousRequisition->status, $openRequisitionStatusList))
                return response()->json([
                    'success' => false,
                    'message' => str_replace(
                        '@re_no',
                        $previousRequisition->req_no,
                        ErrorMessages::vehicleHasActiveRequisition())
                ]);

            $this->checkIfPreviousRequisitionPeriodElapsed($previousRequisition, $valid_from);

            $this->validateOdometerStateValidation($previousRequisition, $requisitionPostRequest);

        }

        /********************** Save Data **************************/
        DB::beginTransaction();

        $user = Auth()->user();
        $documentRef = ReferenceNumberGeneratorService::generateReferenceNumber(
            self::FUEL_REQUISITION_NUMBER_PREFIX,
            1);

        $areaCode = $user->area_code ?? 'LR';
        $requisitionType = 'seq_store_req';
        //$procurementRef = $this->procurementService->generateDocumentNumber($requisitionType, $areaCode);
        $procurementRef = 'J01' . $areaCode . mt_rand(100000, 999999);
        if (empty($procurementRef)) {
            throw new FuelRequisitionException(ErrorMessages::storesRequisitionFailed());
        }

        Log::info('Stores Requisition ' . $procurementRef);

        /*$processDetails = $this->workflowService->startWorkflowProcess(
            $documentRef,
            WorkflowProcessCodes::FuelRequisition->value,
            WorkflowActions::submit(),
            $requisitionPostRequest->get('justification'),
            $user
        );*/

        $message = !empty($documentRef) ?
            ' With Approval Reference ' . $documentRef : '';

        MaterialHeader::create(
            [
                'proc_ref' => $procurementRef,
                'st_pur' => $procurementRef,
                'req_no' => $documentRef,
                'veh_reg_no' => $registrationNumber,
                'cost_centre' => $requisitionPostRequest->get('cost_centre_code'),
                'valid_date_from' => $valid_from,
                'valid_date_to' => $valid_to,
                'odometer' => $requisitionPostRequest->get('odometer_reading'),
                'town_from' => $requisitionPostRequest->get('town_from'),
                'town_to' => $requisitionPostRequest->get('town_to'),
                'date_created' => Carbon::now(),
                'created_by' => $user->id,
                'requested_by' => $user->name,
                'comments' => $requisitionPostRequest->justification,
                'status' => '021',
                'requisition_type' => $requisitionPostRequest->requisition_type,
                'cost_assigned_to' => $requisitionPostRequest->CostAssignedTo == 'CostCenterBasedRequisition' ? 'CostCenter' : 'Project'
            ]
        );

        MaterialDetail::create([
            'created_by' => $user->id,
            'date_created' => Carbon::now(),
            'req_no' => $documentRef,
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

        return response()->json([
            'success' => true,
            'message' => 'Requisition Submitted Successfully. Requisition Number ' . $documentRef,
            'redirectUrl' => URL::signedRoute('show.fuel.requisition', ['ref' => $documentRef])
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
    public function validateCurrentOdometerAgainstInitial($registration_number, $currentOdometer)
    {

        $vehicle = VehicleHeader::where('registration_number', trim($registration_number))->first();
        $chassisDetail = ChassisDetail::where('vehicle_header_id', '=', $vehicle->id)->first();

        if ($chassisDetail->initial_odometer_reading > $currentOdometer) {
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
        $vehicle = VehicleHeader::where('registration_number', '=', $vehicleReference)->first();

        $assignment = Assignment::where('vehicle_header_id', '=', $vehicle->id)->first();

        $responsibleHead = User::where('staff_no', '=', $assignment->responsible_head_id)->first();

        if (empty($responsibleHead) || $responsibleHead->con_st_code != StatusHelper::activeUser()) {
            throw new FuelRequisitionException(ErrorMessages::responsibleUserNotActive, 0);
        }
    }

    /**
     * @param $previousRequisition
     * @param FuelRequisitionPostRequest $requisitionPostRequest
     * @return void
     * @throws FuelRequisitionException
     */
    public function validateOdometerStateValidation($previousRequisition, FuelRequisitionPostRequest $requisitionPostRequest): void
    {
        // verify that odometer reading is not the same as previous requisition

        if (!empty($previousRequisition) && ($requisitionPostRequest->odometer_reading <= $previousRequisition->odometer)) {
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

        if (!empty($previousRequisition) && Carbon::parse($previousRequisition->valid_date_to)->lessThan($valid_from)) {
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
        $results = DB::table('GEN_MATERIAL_HEADERS')->
        where('GEN_MATERIAL_HEADERS.req_no', $req_no)
            ->join('GEN_MATERIAL_DETAILS', 'GEN_MATERIAL_HEADERS.req_no', '=', 'GEN_MATERIAL_DETAILS.req_no')
            ->leftJoin('CONFIG_STATUSES', 'GEN_MATERIAL_HEADERS.status', '=', 'CONFIG_STATUSES.code')
            ->select('GEN_MATERIAL_HEADERS.*', 'GEN_MATERIAL_DETAILS.*', 'CONFIG_STATUSES.name as status_name', 'CONFIG_STATUSES.color_code')
            ->get();

        return $results->first();

    }
}
