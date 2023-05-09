<?php

namespace App\Services\Requisitions;

use App\Enums\VehicleStatusEnum;
use App\Exceptions\FuelRequisitionException;
use App\Helpers\StatusHelper;
use App\Http\Requests\FuelRequisitionPostRequest;
use App\Models\MaterialDetail;
use App\Models\MaterialHeader;
use App\Models\Security\User;
use App\Services\VehicleManagement\VehicleDetailsService;
use App\Services\Workflow\ReferenceNumberGeneratorService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FuelRequisitionService
{

    private VehicleDetailsService $vehicleDetailsService;

    public function __construct(VehicleDetailsService $vehicleDetailsService)
    {
        $this->vehicleDetailsService = $vehicleDetailsService;
    }


    /**
     * @throws FuelRequisitionException
     */
    public function processRequest(FuelRequisitionPostRequest $requisitionPostRequest): JsonResponse
    {

        $registrationNumber = $requisitionPostRequest->vehicle_registration;

        $vehicle = $this->vehicleDetailsService->getBasicVehicleDetails($registrationNumber);

        $this->validateVehicleStatus($vehicle);

        $this->validateVehicleResponsibleUserStatus($vehicle);

        $maximumDistance = ($requisitionPostRequest->material_amount * $vehicle->fuel_consumption) + $requisitionPostRequest->odometer_reading;

        Log::info($maximumDistance . ' distance is');

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
        if (!empty($previousRequisition) && in_array($previousRequisition->status, $openRequisitionStatusList)) {
            return response()->json([
                'success' => false,
                'message' => 'Request failed validation, Vehicle has an open requisition Number '
                    . $previousRequisition->req_no
            ]);
        }

        //$valid_to = null;
        if ($requisitionPostRequest->requisition_type == '011') {
            $valid_to = Carbon::createFromFormat('Y-m-d', $requisitionPostRequest->return_date);
            $valid_from = Carbon::createFromFormat('Y-m-d', $requisitionPostRequest->departure_date);

        } else {
            $valid_to = Carbon::createFromFormat('d/m/Y', $requisitionPostRequest->next_fuel_date);
            $valid_from = Carbon::createFromFormat('d/m/Y', $requisitionPostRequest->request_date);
        }

        $this->checkIfPreviousRequisitionPeriodElapsed($previousRequisition, $valid_from);

        $this->validateOdometerStateValidation($previousRequisition, $requisitionPostRequest);

        //vehicle_registration
        // find last request and validate odometer readings
        DB::beginTransaction();

        $user = Auth()->user();
        $documentRef = ReferenceNumberGeneratorService::generateReferenceNumber(
            "ZFMFUE",
            1,
        );

        /*$workflowService = new WorkflowService();
        $processDetails = $workflowService->startWorkflowProcess(
            $documentRef,
            '202301',
            WorkflowActions::submit(),
            'New Request', auth()->user()
        );

        $message = $processDetails->Reference;*/

        $areaCode = $user->area_code ?? 'GR';
        $procurementRef = 'J01' . $areaCode . mt_rand(100000, 999999);

        MaterialHeader::create(
            [
                'proc_ref' => $procurementRef,
                'st_pur' => $procurementRef,
                'req_no' => $documentRef,
                'reg_no' => $registrationNumber,
                'cost_centre' => $requisitionPostRequest->cost_centre_code,
                'valid_date_from' => $valid_from,
                'valid_date_to' => $valid_to,
                'odometer' => $requisitionPostRequest->odometer_reading,
                'town_from' => $requisitionPostRequest->town_from,
                'town_to' => $requisitionPostRequest->town_to,
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
            'message' => 'Requisition  Submitted Successfully..'
        ]);
    }

    /**
     * @param Model|Builder|null $vehicle
     * @return void
     * @throws FuelRequisitionException
     */
    public function validateVehicleStatus(Model|Builder|null $vehicle): void
    {
        $allowedStatus = [VehicleStatusEnum::active];

        if (!in_array($vehicle->status, $allowedStatus)) {
            throw new FuelRequisitionException(
                "Requisition not accepts while vehicle is not in active state", 0);
        }
    }

    /**
     * @param Model|Builder|null $vehicle
     * @return void
     * @throws FuelRequisitionException
     */
    public function validateVehicleResponsibleUserStatus(Model|Builder|null $vehicle): void
    {
        $responsibleHead = User::where('staff_no', '=', $vehicle->vehicleHolder)->first();

        if ($responsibleHead->con_st_code != StatusHelper::active()) {
            throw new FuelRequisitionException(
                "User Responsible for the vehicle is not active. Your requisition can not be processed",
                0);
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
            throw new FuelRequisitionException("Request failed odometer validation", 0);
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
            throw new FuelRequisitionException("Request failed validation, Previous requisition number " .
                $previousRequisition->req_no . " is still Active. Next Request Date Is "
                . $previousRequisition->valid_date_to, 0);
        }
    }
}
