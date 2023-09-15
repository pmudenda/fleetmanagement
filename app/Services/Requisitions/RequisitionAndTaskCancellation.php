<?php

namespace App\Services\Requisitions;

use App\Enums\RequisitionTypes;
use App\Enums\WorkflowProcessCodes;
use App\Services\Workflow\WorkflowService;

class RequisitionAndTaskCancellation
{
    private WorkflowService $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
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


}
