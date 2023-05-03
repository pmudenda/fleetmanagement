<?php

namespace App\Services\Requisitions;

use App\Helpers\StatusHelper;
use App\Http\Requests\FuelRequisitionPostRequest;
use App\Models\MaterialDetail;
use App\Models\MaterialHeader;
use App\Services\Workflow\ReferenceNumberGeneratorService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FuelRequisitionService
{

    public function processRequest(FuelRequisitionPostRequest $request): JsonResponse
    {

        $registrationNumber = $request->vehicle_registration;

        // check for existing requisition for the same vehicle
        $previousRequisition = MaterialHeader::where('reg_no', $registrationNumber)
            ->whereIn('status', [StatusHelper::new(), StatusHelper::approved()])
            ->orderBy('date_created', 'desc')
            ->first();

        if (!empty($previousRequisition) && $previousRequisition->status == StatusHelper::new()) {
            return response()->json([
                'success' => false,
                'message' => 'Request failed validation, Vehicle has an open requisition'
            ]);
        }

        //$valid_to = null;
        if ($request->requisition_type == '011') {
            $valid_to = Carbon::createFromFormat('Y-m-d', $request->return_date);
            $valid_from = Carbon::createFromFormat('Y-m-d', $request->departure_date);

        } else {
            $valid_to = Carbon::createFromFormat('d/m/Y', $request->next_fuel_date);
            $valid_from = Carbon::createFromFormat('d/m/Y', $request->request_date);
        }

        // check if previous requisition period elapsed
        if (!empty($previousRequisition) && Carbon::parse($previousRequisition->valid_date_to)->lessThan($valid_from)) {
            return response()->json([
                'success' => false,
                'message' => "Request failed validation, Previous requisition still in effect"
            ]);
        }

        // verify that odometer reading is not the same as previous requisition
        if (!empty($previousRequisition) && ($request->odometer_reading <= $previousRequisition->odometer)) {
            return response()->json([
                'success' => false,
                'message' => "Request failed odometer validation"
            ]);
        }

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
                'cost_centre' => $request->cost_centre_code,
                'valid_date_from' => $valid_from,
                'valid_date_to' => $valid_to,
                'odometer' => $request->odometer_reading,
                'town_from' => $request->town_from,
                'town_to' => $request->town_to,
                'date_created' => Carbon::now(),
                'created_by' => $user->id,
                'requested_by' => $user->name,
                'comments' => $request->justification,
                'status' => '021',
                'requisition_type' => $request->requisition_type,
                'cost_assigned_to' => $request->CostAssignedTo == 'CostCenterBasedRequisition' ? 'CostCenter' : 'Project'
            ]
        );

        MaterialDetail::create([
            'created_by' => $user->id,
            'date_created' => Carbon::now(),
            'req_no' => $documentRef,
            'material_code' => $request->material_article_code,
            'quantity' => $request->material_quantity,
            'unit_of_measure' => $request->unit_of_measure,
            'specifications' => $request->material_description,
            'project_code' => $request->project_code ?? $request->projectCode,
            //'project_name' => $request->project_code ?? $request->projectCode,
            //'supplier_code',
            'cost_centre' => $request->cost_centre_code,
            'cost_centre_name' => $request->cost_center_name,
            'reg_no' => $request->vehicle_registration,
            'amount' => $request->material_amount,
            'price' => $request->material_price,
            'max_allowed' => $request->fuel_allocation
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Requisition  Submitted Successfully..'
        ]);
    }
}
