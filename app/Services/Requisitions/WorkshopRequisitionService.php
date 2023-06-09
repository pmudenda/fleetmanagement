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
use App\Models\WorkShopManagement\WorkShopComments;
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
        Log::info("Requisition Ref. " . $requisition_reference_number);
        $form_order_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::PURCHASE_REQUISITION);
        //$document_number = '';
        Log::info("Doc No. " . $form_order_number);
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

        Log::info('Determined Requisition Item Type Code' . $item_type);

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

        $store_code = $requisitionPostRequest->store_code;
        $matHeader = MaterialHeader::create(
            [
                'created_by' => $user->id,
                'date_created' => Carbon::now(),
                //'st_pur' => '',
                //'document_no' => $document_number,
                'status' => StatusHelper::new(),
                'req_no' => $requisition_reference_number,
                'form_order' => $form_order_number,
                'workshop_no' => $requisitionPostRequest->get('workshop_code'),
                'item_type' => $item_type,
                'requested_by' => $user->staff_no,
                'veh_reg_no' => $registrationNumber,
                'purchase_office' => $requisitionPostRequest->get('purchase_office'),
                'store' => $store_code ,
                'supplier_code' => $requisitionPostRequest->supplier,
                'valid_date_from' => $valid_from,
                'valid_date_to' => $valid_to,
                'comments' => $requisitionPostRequest->remarks,
                'cost_assigned_to' => 'CostCenter'
            ]
        );

        foreach ($requisitionPostRequest->get('items') as $item) {

            MaterialDetail::create([
                'created_by' => $user->staff_no,
                'date_created' => Carbon::now(),
                'material_code' => $item->articleCode,
                'unit_of_measure' => $item->unit_of_measure,
                'quantity' => $item->quantity,
                'amount' => $item->total_price,
                'price' => $item->unit_price,
                'cost_centre' => $store_code,
                'req_no' => $requisition_reference_number,
                'specifications' => $item->material_description,
                'reg_no' => $item->registration,
                //'cost_centre_name' => $requisitionPostRequest->store_name ?? 'NA'
            ]);
        }


        WorkShopComments::firstOrCreate(
            [
                'job_card_no' => $requisitionPostRequest->job_card_no,
                'type' => 'REQ',
            ],
            [
                'remarks' => $requisitionPostRequest->remarks,
                'status' => StatusHelper::new(),
                'created_by' => auth()->user()->staff_no
            ]);

        DB::commit();

        // send notification to authoriser
        RequisitionRaised::dispatch($matHeader);
        Log::info('Requisition ' . $requisition_reference_number . ' raised successfully');

        return response()->json([
            'success' => true,
            'message' => 'Requisition '.$requisition_reference_number.' Submitted To. '
                . $authoriser . 'For Approval',
            'redirectUrl' => URL::signedRoute('show.workshop.requisition', [
                'ref' => $requisition_reference_number
            ])
        ]);
    }
}
