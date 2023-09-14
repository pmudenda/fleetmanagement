<?php

namespace App\Services\Workflow;


use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\Priority;
use App\Helpers\StatusHelper;
use App\Models\Common\OrganizationalUnit;
use App\Models\Reference\PHCMSEmployee;
use App\Models\Security\User;
use App\Models\Workflow\WorkflowApprovalLimit;
use App\Models\Workflow\WorkflowLog;
use App\Models\Workflow\WorkflowProcess;
use App\Models\Workflow\WorkflowStep;
use App\Models\Workflow\WorkflowTaskDetail;
use App\Models\Workflow\WorkflowTaskHeader;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkflowService
{
    const APPROVE = 3;
    const REJECT = 2;
    const RESUBMIT = 7;
    const SEND_BACK = 0;
    const PROCESS_MAIN_DATA_NOT_FOUND = "Approval Process Heading Data Not Found";
    const PROCESS_STEP_DATA_IS_MISSING = "Approval Process Current State Data Is Missing";
    const APPROVAL_PROCESS_CURRENT_STATE_RECORD_NOT_FOUND = "Approval Process Current State Record Not Found";
    const APPROVAL_PROCESS_NEXT_STATE_RECORD_NOT_FOUND = "Approval Process Next State Record Not Found";


    /**
     * Initialize Approval task
     * @param string $taskReference
     * @param int $processCode
     * @param int $action
     * @param string $comment
     * @param $amount
     * @param string $short_description
     * @param string $long_description
     * @param string|null $assignTo
     * @return WorkflowTaskDetail
     * @throws WorkflowTaskCreationFailedException
     */
    public function initiateWorkflowProcess(string $taskReference,
                                            int    $processCode,
                                            int    $action,
                                            string $comment,
                                                   $amount,
                                            string $short_description,
                                            string $long_description,
                                            string $assignTo = null
    ): WorkflowTaskDetail
    {
        $currentUser = Auth()->user();

        Log::info(
            'Reference ' . $taskReference
            . ' Process Code ' . $processCode
            . ' Action ' . $action
            . ' Comment '
            . $comment . ' Amount ' . $amount);

        $process = WorkflowProcess::where('process_code', $processCode)->first();

        if (empty($process)) {
            throw new WorkflowTaskCreationFailedException("Process not Found");
        }

        // get the first step in this process
        $processFirstStep = WorkflowStep::where(
            'process_id', '=', $processCode)
            ->where('is_initial_step', true)
            ->where('is_initial_step', '=', 1)
            ->first();

        if ($processFirstStep == null) {
            throw new WorkflowTaskCreationFailedException("Could not Determine Initial Step");
        }

        if ($processFirstStep->next_step == null) {
            throw new WorkflowTaskCreationFailedException("Could not Determine Next Step Id");
        }

        $stepAfterSubmission = WorkflowStep::where('process_id', '=', $processCode)
            ->where('step_id', '=', $processFirstStep->next_step)->first();

        if ($stepAfterSubmission == null) {
            throw new WorkflowTaskCreationFailedException(
                "Could not Determine Next Step"
            );
        }

        $userUnit = $this->getUserUnit($currentUser);

        // audit trail for submission of task
        $this->createLog(
            $comment,
            $currentUser,
            $action,
            "Create Document",
            StatusHelper::submitted(),
            $stepAfterSubmission,
            $taskReference
        );

        /****************************** Determine User to assign task ******************************************/
        if (empty($assignTo)) {
            $assignToUser = $this->getApprovingOfficer($currentUser);
        } else {
            $assignToUser = PHCMSEmployee::where('con_st_code', '=', 'ACT')
                ->where('alt_per_no', '=', $assignTo)
                ->first();
        }

        $actionPage = $stepAfterSubmission->action_page;

        //'date_acted'
        WorkflowTaskHeader::create([
            'assigned_user' => $assignToUser->alt_per_no ?? $assignToUser->staff_no,
            'subject' => $short_description,
            'status' => StatusHelper::new(),
            'url' => $actionPage,
            'reference' => $taskReference,
            'priority' => Priority::high(),
            'description' => $comment, //'You have received a fuel requisition approval task ',
            'long_description' => $long_description,
            'created_by' => $currentUser->id,
            'date_acted' => Carbon::now(),
            'process_code' => $processCode,
            'amount' => str_replace(',', '', $amount),
            'user_unit' => $userUnit
        ]);

        return WorkflowTaskDetail::create([
            'reference' => $taskReference,
            'process_code' => $processCode,
            'user_id' => $currentUser->staff_no,
            'current_step_id' => $stepAfterSubmission->step_id,
            'actioning_officer' => $assignToUser->con_per_no ?? $assignToUser->staff_no,
            'status' => StatusHelper::new(),
            'step_after_submission' => $actionPage,
            'date_started' => Carbon::now(),
            'created_by' => $currentUser->staff_no
        ]);

    }


    /**
     * @throws WorkflowTaskCreationFailedException
     */
    public function invokeWorkFlow(string $reference,
                                   string $processId,
                                   int    $action,
                                   string $actionTaken,
                                   string $comment
    ): array
    {

        Log::info('Processing Workflow Reference ' . $reference);
        Log::info('Workflow Process Code ' . $processId);

        // get workflow process header for the task
        $taskHeader = WorkflowTaskHeader::where(
            'reference', '=', trim($reference)
        )->where('process_code', '=', $processId)
            ->first();

        // get workflow process detail
        $taskDetail = WorkflowTaskDetail::where(
            'reference', '=', trim($reference))
            ->where('process_code', '=', $processId)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($taskDetail)) {
            throw new WorkflowTaskCreationFailedException(
                "Approval Process Details Not Found",
                100
            );
        }

        if (empty($taskHeader)) {
            throw new WorkflowTaskCreationFailedException(
                self::PROCESS_MAIN_DATA_NOT_FOUND,
                100
            );
        }

        if (empty($taskDetail->current_step_id)) {
            throw new WorkflowTaskCreationFailedException(
                self::PROCESS_STEP_DATA_IS_MISSING,
                101
            );
        }

        // always start at current position
        $currentStep = WorkflowStep::where(
            'step_id',
            '=', $taskDetail->current_step_id
        )->where('process_id', '=', $processId)->first();

        // update workflow log
        if (empty($currentStep)) {
            throw new WorkflowTaskCreationFailedException(
                self::APPROVAL_PROCESS_CURRENT_STATE_RECORD_NOT_FOUND,
                102
            );
        }

        Log::info("Action Passed is " . $action);
        Log::info("Action Taken " . $actionTaken);

        $userUnit = $taskHeader->user_unit ?? 'G1500';

        $lastStep = $this->getApprovalLimit($userUnit, $taskHeader->amount);

        switch ($action) {
            case self::REJECT:
                $response = $this->rejectRequest(
                    $comment,
                    $action,
                    $actionTaken,
                    $currentStep,
                    $taskHeader,
                    $taskDetail);
                break;
            case self::APPROVE:
                $response = $this->approveRequest(
                    $currentStep,
                    $lastStep,
                    $comment,
                    $action,
                    $actionTaken,
                    $taskHeader,
                    $taskDetail);
                break;
            case self::SEND_BACK:
                $response = $this->sendBackRequest(
                    $reference,
                    $processId,
                    $taskDetail
                );
                break;
            case self::RESUBMIT:
                $response = $this->resubmitRequest($taskDetail);
                break;
            default:
                $taskDetail->current_step_id = null;
                $taskDetail->actioning_officer = null;
                self::closePreviousTasks($taskDetail);
                $taskDetail->save();
                $response = [$taskDetail->current_step_id, '0'];
                break;
        }

        return $response;
    }

    public function getMyApprovalTasks($staff_no): Collection
    {
        return DB::table('WFL_WORKFLOW_TASK')
            ->leftJoin('SEC_USERS', 'WFL_WORKFLOW_TASK.created_by', '=', 'SEC_USERS.id')
            ->where('WFL_WORKFLOW_TASK.assigned_user', '=', $staff_no)
            ->whereNull('WFL_WORKFLOW_TASK.date_ended')
            ->select('WFL_WORKFLOW_TASK.*', 'SEC_USERS.name as originator')
            ->orderBy('WFL_WORKFLOW_TASK.created_at', 'desc')
            ->get();
    }

    /**
     * @param $currentUser
     * @return mixed
     * @throws WorkflowTaskCreationFailedException
     */
    public function getApprovingOfficer($currentUser): mixed
    {
        /****************************Determine User to assign task*************************************************/
        if (empty($currentUser->supervisor_code)) {
            throw new WorkflowTaskCreationFailedException("Supervisor Not Assigned Found");
        }

        $assignToUser = User::where(
            'staff_no',
            '=',
            $currentUser->supervisor_code)
            //->where('con_st_code', 'ACT')
            ->first();

        if (empty($assignToUser)) {
            throw new WorkflowTaskCreationFailedException("Supervisor Is Not A Fleet Master User");
        }

        return $assignToUser;
    }

    /**
     * @param string $comment
     * @param int $action
     * @param string $actionTaken
     * @param $currentStep
     * @param $taskHeader
     * @param $taskDetail
     * @return array
     */
    public function rejectRequest(string $comment,
                                  int    $action,
                                  string $actionTaken,
                                         $currentStep,
                                         $taskHeader,
                                         $taskDetail): array
    {
        $currentUser = auth()->user();

        $this->createLog(
            $comment,
            $currentUser,
            $action,
            $actionTaken,
            StatusHelper::rejected(),
            $currentStep,
            $taskDetail->reference
        );

        $taskHeader->date_ended = Carbon::now();
        $taskHeader->status = StatusHelper::rejected();
        $taskHeader->save();

        $taskDetail->date_ended = Carbon::now();
        $taskDetail->save();

        return [100, "0"];
    }

    /**
     * @param $currentStep
     * @param string $lastStep
     * @param string $comment
     * @param int $action
     * @param string $actionTaken
     * @param $taskHeader
     * @param $taskDetail
     * @return array
     * @throws WorkflowTaskCreationFailedException
     */
    public function approveRequest(
        $currentStep,
        string $lastStep,
        string $comment,
        int $action,
        string $actionTaken,
        $taskHeader,
        $taskDetail
    ): array
    {
        $currentUser = auth()->user();

        if ($this->isFinalStep($currentStep, $lastStep)) {

            Log::info("Final Step Approving and Ending Process");

            $this->createLog(
                $comment,
                $currentUser,
                $action,
                $actionTaken,
                StatusHelper::authorised(),
                $currentStep,
                $taskDetail->reference
            );

            $taskHeader->date_ended = Carbon::now();
            $taskHeader->status = StatusHelper::approved();
            $taskHeader->save();

            $taskDetail->date_ended = Carbon::now();
            $taskDetail->save();

            return [100, '0'];
        }

        Log::info("Workflow Step Not Final ");
        // get step
        $nextStep = WorkflowStep::where('step_id', '=', $currentStep->next_step)
            ->where('process_id', '=', $taskHeader->process_code)
            ->first();

        Log::info("Next Step Determined As " . $nextStep->step_id);

        if (empty($nextStep)) {
            throw new WorkflowTaskCreationFailedException(
                self::APPROVAL_PROCESS_NEXT_STATE_RECORD_NOT_FOUND,
                102
            );
        }

        // create partial authorisation log
        Log::info("Creating Approval Log ");

        $this->createLog(
            $comment,
            $currentUser,
            $action,
            $actionTaken,
            StatusHelper::partiallyAuthorised(),
            $currentStep,
            $taskDetail->reference
        );

        $taskDetail->current_step_id = $nextStep->step_id;

        // is supervisor of
        $approvingOfficer = $this->getApprovingOfficer($currentUser);

        $assignToUser = $approvingOfficer;
        Log::info("Next Authorising Authority Determined as " . $assignToUser->staff_no);

        if ($assignToUser->staff_no != 0) {
            $taskDetail->actioning_officer = $assignToUser->staff_no;
        }

        $taskDetail->save();
        $taskHeader->assigned_user = $assignToUser->staff_no;
        $taskHeader->save();

        Log::info("Returning Next Step Id " . $nextStep->step_id);

        return [$nextStep->step_id, $assignToUser->name];
    }

    /**
     * @param $currentStep
     * @param string $lastStep
     * @return bool
     */
    public function isFinalStep($currentStep, string $lastStep): bool
    {
        return $currentStep->is_final_step
            || $currentStep->is_final_step == '1'
            || $currentStep->is_final_step == 1
            || $currentStep == $lastStep;
    }

    public function cancelProcessTask($req_no, $process_id): void
    {
        DB::beginTransaction();

        // get workflow process header
        $task_header = WorkflowTaskHeader::where('reference', '=', $req_no)
            ->where('process_code', '=', $process_id)
            ->first();

        // get workflow process detail
        $task_detail = WorkflowTaskDetail::where('reference', '=', $req_no)
            ->where('process_code', '=', $process_id)
            ->orderBy('id', 'desc')
            ->first();

        if ($task_header) {
            $task_header->date_ended = Carbon::now();
            $task_header->status = StatusHelper::cancelled();
            $task_header->save();
        }

        if ($task_detail) {
            $task_detail->date_ended = Carbon::now();
            $task_detail->save();
        }

        DB::commit();
    }

    private function getApprovalLimit($user_unit, $amount)
    {
        $result = WorkflowApprovalLimit::where('user_unit_code', '=', $user_unit)
            ->where(function ($query) use ($amount) {
                return $query->where('approval_lower_limit', '<=', $amount)
                    ->where('approval_upper_limit', '>=', $amount);
            })
            ->first();

        if (!$result) {
            Log::info('Amount based Last Step Not Found');
            return '01';
        }

        return $result->final_step;
    }

    private function closePreviousTasks(WorkflowTaskDetail $process): void
    {
        $existingNotifications = WorkflowTaskHeader::where('reference', '=', $process->reference)->get();

        foreach ($existingNotifications as $existingNotification) {
            $existingNotification->status = StatusHelper::closed();
            $existingNotification->save();
        }

    }

    /**
     * Retrieves the code unit of the user
     * @throws WorkflowTaskCreationFailedException
     */
    private function getUserUnit($currentUser)
    {
        $ou = OrganizationalUnit::where('cc_code', '=', $currentUser->cc_code)
            ->where('bu_code', '=', $currentUser->bu_code)
            ->first();
        if (!$ou) {
            throw new WorkflowTaskCreationFailedException("User Unit Not Found");
        }

        return $ou->code_unit;
    }

    private function sendBackRequest($reference, $processId, $taskDetail): array
    {
        Log::info("Sending Request Back ");
        Log::info("Reference " . $reference);
        Log::info("Process Code " . $processId);

        // send back
        $firstStep = WorkflowStep::where(
            'process_id',
            '=',
            $processId
        )->where(
            'is_initial_step',
            '=',
            "1"
        )->first();

        $firstStepLog = WorkflowLog::where(
            'reference',
            '=',
            $reference
        )->where(
            'step_id',
            '=',
            $firstStep->step_id
        )->first();

        $taskDetail->current_step_id = $firstStep->step_id;
        $taskDetail->actioning_officer = $firstStepLog->actioning_officer;
        $taskDetail->save();

        return [$taskDetail->current_step_id, $firstStepLog->actioning_officer];
    }


    /**
     * @param $comment
     * @param $currentUser
     * @param $action
     * @param $activity
     * @param $status
     * @param $step
     * @param $taskReference
     * @return void
     */
    public function createLog($comment,
                              $currentUser,
                              $action,
                              $activity,
                              $status,
                              $step,
                              $taskReference
    ): void
    {
        WorkflowLog::create([
            'reference' => $taskReference,
            'step_id' => $step->step_id,
            'actioning_officer' => $currentUser->staff_no,
            'action' => $action,
            'activity' => $activity,
            'status' => $status,
            'action_date' => Carbon::now(),
            'next_step' => $step->step_id,
            'previous_step' => $step->previous_step ?? '00',
            'remarks' => $comment
        ]);
    }

    /**
     * @param $taskDetail
     * @return array
     */
    public function resubmitRequest($taskDetail): array
    {
        $taskDetail->current_step_id = null;
        $taskDetail->actioning_officer = null;
        $taskDetail->save();


        return [$taskDetail->current_step_id, '0'];
    }

}
