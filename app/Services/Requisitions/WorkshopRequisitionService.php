<?php

namespace App\Services\Requisitions;

use App\Enums\RequisitionItemTypes;
use App\Enums\WorkflowProcessCodes;
use App\Events\RequisitionRaised;
use App\Exceptions\FuelRequisitionException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Requests\WorkshopRequisitionRequest;
use App\Models\MaterialDetail;
use App\Models\MaterialHeader;
use App\Models\Workflow\WorkflowActions;
use App\Models\Workflow\WorkflowModules;
use App\Models\WorkShopManagement\JobCardHeader;
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

        /********************************************** Save Data **************************************/
        $user = Auth()->user();

        $requisition_reference_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::WORKSHOP_REQUISITION);
        Log::info("Requisition Ref. " . $requisition_reference_number);
        $form_order_number = DocumentNumberGenerationService::generateReferenceNumber(WorkflowModules::PURCHASE_REQUISITION);
        //$document_number = '';
        Log::info("Doc No. " . $form_order_number);
        Log::info('Requisition Item Type ' . $requisitionPostRequest->get('itemType'));

        $workflowProcess = '';
        $item_type = "";
        switch ($requisitionPostRequest->itemType) {
            case RequisitionItemTypes::StockItemCode:
                $item_type = RequisitionItemTypes::StockItem;
                $workflowProcess = WorkflowProcessCodes::StoresRequisition->value;
                break;
            case RequisitionItemTypes::NonStockItemCode:
                $item_type = RequisitionItemTypes::NonStockItem;
                $workflowProcess = WorkflowProcessCodes::StoresRequisition->value;
                break;
            case RequisitionItemTypes::ServiceItemCode:
                $item_type = RequisitionItemTypes::Service;
                $workflowProcess = WorkflowProcessCodes::StoresRequisition->value;
                break;
        }

        Log::info('Determined Requisition Item Type Code ' . $item_type);

        $short_description = "Workshop Requisition for Vehicle Reg No. " . $registrationNumber;
        $long_description = "Workshop Requisition Ref.No. " . $requisition_reference_number . " For Vehicle Reg No. " . $registrationNumber;

        $authority = 'GhostInCode';

        $justification = $requisitionPostRequest->remarks;

        $this->workflowService->initiateWorkflowProcess(
            $requisition_reference_number,
            (int)$workflowProcess,
            WorkflowActions::submit(),
            $justification,
            $user,
            0,//$requisitionPostRequest->material_amount,
            $short_description,
            $long_description
        );

        $store_code = $requisitionPostRequest->store_code;
        $job_cord_no = $requisitionPostRequest->job_card_no;

        $matHeader = MaterialHeader::create(
            [
                'created_by' => $user->id,
                'date_created' => Carbon::now(),
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
                'comments' => $justification,
                'cost_assigned_to' => 'CostCenter'
            ]
        );

        foreach ($requisitionPostRequest->get('items') as $item) {
            MaterialDetail::create([
                'created_by' => $user->staff_no,
                'date_created' => Carbon::now(),
                'material_code' => $item['articleCode'],
                'unit_of_measure' => $item['unit_of_measure'],
                'quantity' => $item['quantity'],
                'amount' => $item['total_price'],
                'price' => $item['unit_price'],
                'cost_centre' => $store_code,
                'req_no' => $requisition_reference_number,
                'specifications' => $item['technical_specification'],
                'reg_no' => $item['registration'],
            ]);
        }

        WorkShopComments::firstOrCreate(
            [
                'job_card_no' => $job_cord_no,
                'type' => 'REQ',
            ],
            [
                'remarks' => $requisitionPostRequest->remarks,
                'status' => StatusHelper::new(),
                'created_by' => auth()->user()->staff_no
            ]);


        // Link Requisition and Job Card
        JobCardHeader::where('job_card_no', $job_cord_no)
            ->update(['req_no' => $requisition_reference_number]);

        DB::commit();

        // send notification to authoriser
        RequisitionRaised::dispatch($matHeader);
        Log::info('Requisition ' . $requisition_reference_number . ' raised successfully');

        return response()->json([
            'success' => true,
            'message' => 'Requisition '.$requisition_reference_number.' Submitted To. '
                . $authority . 'For Approval',
            'redirectUrl' => URL::signedRoute('show.workshop.requisition', [
                'ref' => $requisition_reference_number
            ])
        ]);
    }
}
