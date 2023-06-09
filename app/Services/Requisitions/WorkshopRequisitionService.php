<?php

namespace App\Services\Requisitions;

use App\Enums\RequisitionItemTypes;
use App\Events\RequisitionRaised;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Requests\WorkshopRequisitionRequest;
use App\Models\MaterialDetail;
use App\Models\MaterialHeader;
use App\Models\Workflow\WorkflowModules;
use App\Services\Integration\ProcurementSystemIntegrationService;
use App\Services\VehicleManagement\VehicleDetailsService;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\Workflow\WorkflowService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class WorkshopRequisitionService
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
    public function processRequest(WorkshopRequisitionRequest $requisitionPostRequest): JsonResponse
    {
        Log::info("Creating Workshop Request");

        DB::beginTransaction();

        $valid_to = Carbon::parse($requisitionPostRequest->get('date_expected')) ?? Carbon::now()->addDays(7);
        $valid_from = Carbon::now();
        $registrationNumber = "";

        //$maximumDistance = ($requisitionPostRequest->material_amount * $vehicle->fuel_consumption) + $requisitionPostRequest->odometer_reading;
        //Log::info($maximumDistance . ' distance is');

        //Log::info($registrationNumber);
        /********************************************** Save Data **************************************/
        $user = Auth()->user();

        $requisition_reference_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::WORKSHOP_REQUISITION);
        Log::info("Requisition Ref. ". $requisition_reference_number);
        $form_order_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::PURCHASE_REQUISITION);
        //$document_number = '';
        Log::info("Doc No. ". $form_order_number);
        $workflowProcess = '';
        $item_type = "";

        Log::info('Requisition Item Type ' . $requisitionPostRequest->get('itemType'));

        //$workflowProcess = WorkflowProcessCodes::OutOfTownFuelRequisition->value;
        //$workflowProcess = WorkflowProcessCodes::NormalFuelRequisition->value;
        //$workflowProcess = WorkflowProcessCodes::OverrideFuelRequisition->value;

        switch ($requisitionPostRequest->itemType) {
            case RequisitionItemTypes::StockItemCode:
                $item_type = RequisitionItemTypes::StockItem;
                break;
            case RequisitionItemTypes::NonStockItemCode:
                $item_type = RequisitionItemTypes::NonStockItem;
                break;
            case RequisitionItemTypes::ServiceItemCode:
                $item_type = RequisitionItemTypes::Service;
                break;
        }

        Log::info('Determined Requisition Item Type Code' . $$item_type);

        $short_description = ""; //"Fuel Requisition For Vehicle Reg No. " . $registrationNumber;
        $long_description = ""; //"Fuel Requisition Ref.No. " . $requisition_reference_number . " For Vehicle Reg No. " . $registrationNumber;
        $authoriser = 'Lovemore';
        /*$this->workflowService->initiateWorkflowProcess(
            $requisition_reference_number,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $requisitionPostRequest->get('justification'),
            $user,
            $requisitionPostRequest->material_amount,
            $short_description,
            $long_description
        );*/


        $matHeader = MaterialHeader::create(
            [
                'created_by' => $user->id,
                'date_created' => Carbon::now(),
                //'st_pur' => '',
                'status' => StatusHelper::new(),

                'req_no' => $requisition_reference_number,
                'form_order' => $form_order_number,
                //'document_no' => $document_number,
                'workshop_no' => $requisitionPostRequest->get('workshop_code'),

                'item_type' => $item_type,
                'requested_by' => $user->staff_no,
                'cost_centre' => $requisitionPostRequest->get('store_code') ?? 'N', //rename to business unit/code_unit
                'veh_reg_no' => $registrationNumber,
                'purchase_office' => $requisitionPostRequest->get('purchase_office'),
                'store' => $requisitionPostRequest->store_code,
                'supplier_code' => $requisitionPostRequest->supplier,

                'valid_date_from' => $valid_from,
                'valid_date_to' => $valid_to,
                //'odometer' => $requisitionPostRequest->get('odometer_reading'),
                //'town_from' => $requisitionPostRequest->has('departureTown') ? $requisitionPostRequest->get('departureTown') : null,
                //'town_to' => $requisitionPostRequest->has('destinationTown') ? $requisitionPostRequest->get('destinationTown') : null,

                'comments' => $requisitionPostRequest->remarks,
                //'requisition_type' => $requisitionPostRequest->requisition_type,
                'cost_assigned_to' => 'CostCenter'
            ]
        );

        foreach ($requisitionPostRequest->get('items') as $item) {
            MaterialDetail::create([
                'created_by' => $user->staff_no,
                'date_created' => Carbon::now(),
                'material_code' => $requisitionPostRequest->articles,

                'unit_of_measure' => $requisitionPostRequest->unit_of_measure,

                'quantity' => $requisitionPostRequest->quantity,
                'amount' => $requisitionPostRequest->total_price,
                'price' => $requisitionPostRequest->unit_price,

                'cost_centre' => $requisitionPostRequest->store_code,
                'req_no' => $requisition_reference_number,
                'specifications' => $requisitionPostRequest->material_description,
                'reg_no' => $requisitionPostRequest->registration,

                'cost_centre_name' => $requisitionPostRequest->store_name ?? 'NA'
            ]);
        }

        DB::commit();

        // send notification to authoriser
        RequisitionRaised::dispatch($matHeader);
        Log::info('Requisition ' . $requisition_reference_number . ' raised successfully');

        return response()->json([
            'success' => true,
            'message' => 'Requisition Submitted For Approval To. '
                . $authoriser . ' Requisition Number ' . $requisition_reference_number,
            'redirectUrl' => URL::signedRoute('show.fuel.requisition', [
                'ref' => $requisition_reference_number
            ])
        ]);
    }
}
