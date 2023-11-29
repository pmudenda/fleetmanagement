<?php

namespace App\Services\Workflow;


use App\Constants\QueryComparisonOperator;
use App\Constants\TableColumns;
use App\Enums\WorkflowProcessCodes;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\Priority;
use App\Helpers\StatusHelper;
use App\Helpers\TaskStatus;
use App\Models\Common\OrganizationalUnit;
use App\Models\Reference\PHCMSEmployee;
use App\Models\Security\User;
use App\Models\Workflow\WorkflowApprovalLimit;
use App\Models\Workflow\WorkflowLog;
use App\Models\Workflow\WorkflowProcess;
use App\Models\Workflow\WorkflowStep;
use App\Models\Workflow\WorkflowTaskDetail;
use App\Models\Workflow\WorkflowTaskHeader;
use App\Services\Security\ProfileDelegationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkflowService
{
    const APPROVE = 3;
    const REJECT = 2;
    const RESUBMIT = 5;
    const SEND_BACK = 0;
    const PROCESS_MAIN_DATA_NOT_FOUND = "Approval Process Heading Data Not Found";
    const PROCESS_STEP_DATA_IS_MISSING = "Approval Process Current State Data Is Missing";
    const APPROVAL_PROCESS_CURRENT_STATE_RECORD_NOT_FOUND = "Approval Process Current State Record Not Found";
    const APPROVAL_PROCESS_NEXT_STATE_RECORD_NOT_FOUND = "Approval Process Next State Record Not Found";
    private ProfileDelegationService $profileDelegationService;

    public function __construct(ProfileDelegationService $profileDelegationService)
    {
        $this->profileDelegationService = $profileDelegationService;
    }


    /**
     * Initialize Approval task
     * @param string $taskReference
     * @param int $processCode
     * @param int $action
     * @param string $comment
     * @param $amount
     * @param array $description
     * @param string|null $assignTo
     * @return WorkflowTaskDetail
     * @throws WorkflowTaskCreationFailedException
     */
    public function initiateWorkflowProcess(string $taskReference,
                                            int    $processCode,
                                            int    $action,
                                            string $comment,
                                                   $amount,
                                            array  $description,
                                            string $assignTo = null
    ): WorkflowTaskDetail
    {
        $short_description = $description[0];
        $long_description = $description[1];
        $currentUser = Auth()->user();
        Log::info("===================================Starting Workflow===================================");
        Log::info(
            'Reference ' . $taskReference
            . ' Process Code ' . $processCode
            . ' Action ' . $action
            . ' Comment '
            . $comment . ' Amount ' . $amount
        );

        $process = WorkflowProcess::where('process_code',
            QueryComparisonOperator::EQUALS,
            $processCode)->first();

        if (empty($process)) {
            throw new WorkflowTaskCreationFailedException("Process not Found");
        }

        // get the first step in this process
        $processFirstStep = WorkflowStep::where(
            'process_id',
            QueryComparisonOperator::EQUALS,
            $processCode
        )->where('is_initial_step', true)
            ->where('is_initial_step',
                QueryComparisonOperator::EQUALS,
                1)->first();

        if ($processFirstStep == null) {
            throw new WorkflowTaskCreationFailedException("Could not Determine Initial Step");
        }

        if ($processFirstStep->next_step == null) {
            throw new WorkflowTaskCreationFailedException("Could not Determine Next Step Id");
        }

        $stepAfterSubmission = WorkflowStep::where('process_id',
            QueryComparisonOperator::EQUALS, $processCode)
            ->where('step_id',
                QueryComparisonOperator::EQUALS,
                $processFirstStep->next_step)->first();

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
            TaskStatus::submitted(),
            $processFirstStep,
            $taskReference
        );

        /****************************** Determine User to assign task ******************************************/
        if (empty($assignTo)) {
            $assignToUser = $this->getApprovingOfficer($currentUser);

        } else {
            $assignToUser = PHCMSEmployee::where('con_st_code',
                QueryComparisonOperator::EQUALS,
                'ACT')
                ->where(function (Builder $query) use ($assignTo) {
//                    $query->where('alt_per_no', QueryComparisonOperator::EQUALS, $assignTo);
                    $query->where('con_per_no', QueryComparisonOperator::EQUALS, $assignTo);
                })
                ->first();
        }

        $actionPage = $stepAfterSubmission->action_page;

        WorkflowTaskHeader::create([
            'assigned_user' =>  $assignToUser->con_per_no,
            'subject' => $short_description,
            'status' => StatusHelper::new(),
            'url' => $actionPage,
            'reference' => $taskReference,
            'priority' => Priority::high(),
            'description' => $comment,
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
            'actioning_officer' => $assignToUser->con_per_no,
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
                                   string $comment,
                                          $subject
    ): array
    {

        Log::debug('Processing Workflow Reference ' . $reference);
        Log::debug('Workflow Process Code ' . $processId);
        Log::info($subject);
        // get workflow process header for the task
        $taskHeader = WorkflowTaskHeader::where(
            TableColumns::REFERENCE,
            QueryComparisonOperator::EQUALS,
            trim($reference)
        )->where(
            TableColumns::HEADER_PROCESS_CODE_COLUMN,
            QueryComparisonOperator::EQUALS,
            $processId)
            ->first();

        // get workflow process detail
        $taskDetail = WorkflowTaskDetail::where(
            TableColumns::REFERENCE,
            QueryComparisonOperator::EQUALS,
            trim($reference))
            ->where(
                TableColumns::HEADER_PROCESS_CODE_COLUMN,
                QueryComparisonOperator::EQUALS,
                $processId
            )
            ->orderBy('id', 'desc')
            ->first();

        if (empty($taskDetail)) {
            throw new WorkflowTaskCreationFailedException(
                "Approval Process Details Not Found"

            );
        }

        if (empty($taskHeader)) {
            throw new WorkflowTaskCreationFailedException(
                self::PROCESS_MAIN_DATA_NOT_FOUND

            );
        }

        if (empty($taskDetail->current_step_id)) {
            throw new WorkflowTaskCreationFailedException(
                self::PROCESS_STEP_DATA_IS_MISSING

            );
        }

        // always start at current position
        $currentStep = WorkflowStep::where(
            'step_id',
            QueryComparisonOperator::EQUALS,
            $taskDetail->current_step_id
        )->where('process_id',
            QueryComparisonOperator::EQUALS,
            $processId)->first();

        // update workflow log
        if (empty($currentStep)) {
            throw new WorkflowTaskCreationFailedException(
                self::APPROVAL_PROCESS_CURRENT_STATE_RECORD_NOT_FOUND,
                102
            );
        }

        Log::debug("Action Passed is " . $action);
        Log::debug("Action Taken " . $actionTaken);

        $userUnit = $taskHeader->user_unit;

        $lastStep = $this->getApprovalLimit($userUnit, $taskHeader->amount);

        switch ($action) {
            case self::REJECT:
                $response = $this->rejectRequest(
                    $comment,
                    $action,
                    $actionTaken,
                    $currentStep,
                    $taskHeader,
                    $taskDetail
                );
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
                    $comment,
                    $action,
                    $actionTaken,
                    $taskHeader,
                    $taskDetail,
                    $currentStep
                );
                break;
            case self::RESUBMIT:
                $response = $this->resubmitRequest(
                    $taskDetail,
                    $taskHeader,
                    $comment,
                    $action,
                    $actionTaken,
                    $currentStep
                );
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

    public function getMyApprovalTasks($user): Collection
    {
        $staffNumber = $user->staff_no;

        $delegatedProfileOwner = $this->profileDelegationService->getDelegatedProfileOwner($user->id);

        return DB::table('WFL_WORKFLOW_TASK task_header')
            ->leftJoin('SEC_USERS users',
                'task_header.created_by',
                QueryComparisonOperator::EQUALS,
                'users.id')
            ->where(function ($query) use ($staffNumber, $delegatedProfileOwner) {
                $query->where(
                    'task_header.assigned_user',
                    QueryComparisonOperator::EQUALS,
                    $staffNumber
                )->orWhere(
                    'task_header.assigned_user',
                    QueryComparisonOperator::EQUALS,
                    $delegatedProfileOwner
                );
            })
            ->whereNull('task_header.date_ended')
            ->select('task_header.*',
                'users.name as originator')
            ->orderBy('task_header.created_at', 'desc')
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
        $finalStep = false;

        Log::info("Running New Approval Logic");
        if (auth()->user()->can(config('rights.final_authoriser'))
            && $taskHeader->process_code == WorkflowProcessCodes::OutOfTownFuelRequisition->value) {
            $finalStep = true;
            Log::info("User Has OOT Final Authority.. Finally Approving Process ");
        } elseif (auth()->user()->can(config('rights.final_authoriser'))
            && $taskHeader->process_code == WorkflowProcessCodes::LocalFuelRequisition->value) {
            $finalStep = true;
            Log::info("User Has Local Final Authority.. Finally Approving Process ");
        } else {
            $finalStep = $this->isFinalStep($currentStep, $lastStep);
        }

        if ($finalStep) {

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
        $nextStep = WorkflowStep::where('step_id',
            QueryComparisonOperator::EQUALS,
            $currentStep->next_step)
            ->where('process_id',
                QueryComparisonOperator::EQUALS,
                $taskHeader->process_code)
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
        $task_header = WorkflowTaskHeader::where('reference',
            QueryComparisonOperator::EQUALS, $req_no)
            ->where(TableColumns::HEADER_PROCESS_CODE_COLUMN,
                QueryComparisonOperator::EQUALS,
                $process_id)
            ->first();

        // get workflow process detail
        $task_detail = WorkflowTaskDetail::where('reference',
            QueryComparisonOperator::EQUALS, $req_no)
            ->where(TableColumns::HEADER_PROCESS_CODE_COLUMN,
                QueryComparisonOperator::EQUALS,
                $process_id)
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

    /** Logic Needs refinement
     * @param $user_unit
     * @param $amount
     * @return string
     */
    private function getApprovalLimit($user_unit, $amount)
    {
        $result = WorkflowApprovalLimit::where('user_unit_code',
            QueryComparisonOperator::EQUALS,
            $user_unit)
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
        $existingNotifications = WorkflowTaskHeader::where(
            TableColumns::REFERENCE,
            QueryComparisonOperator::EQUALS,
            $process->reference
        )->get();

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
        $ou = OrganizationalUnit::where(
            'cc_code',
            QueryComparisonOperator::EQUALS,
            $currentUser->cc_code)
            ->where('bu_code',
                QueryComparisonOperator::EQUALS,
                $currentUser->bu_code)
            ->first();
        if (!$ou) {
            throw new WorkflowTaskCreationFailedException("User Unit Not Found");
        }

        return $ou->code_unit;
    }

    private function sendBackRequest($comment,
                                     $action,
                                     $actionTaken,
                                     $taskHeader,
                                     $taskDetail,
                                     $currentStep): array
    {
        Log::info("Sending Request Back ");
        Log::info("Reference " . $taskHeader->reference);
        Log::info("Process Code " . $taskHeader->process_code);

        // send back
        $firstStep = WorkflowStep::where(
            'process_id',
            QueryComparisonOperator::EQUALS,
            $taskHeader->process_code
        )->where(
            'is_initial_step',
            QueryComparisonOperator::EQUALS,
            "1"
        )->first();

        $firstStepLog = WorkflowLog::where(
            TableColumns::REFERENCE,
            QueryComparisonOperator::EQUALS,
            $taskHeader->reference
        )->where(
            'step_id',
            QueryComparisonOperator::EQUALS,
            $firstStep->step_id
        )->first();

        $currentUser = auth()->user();

        $this->createLog(
            $comment,
            $currentUser,
            $action,
            $actionTaken,
            TaskStatus::sentBack(),
            $currentStep,
            $taskDetail->reference
        );

        $taskDetail->current_step_id = $firstStep->step_id;
        $taskDetail->actioning_officer = $firstStepLog->actioning_officer;
        $taskDetail->save();

        $taskHeader->url = $firstStep->action_page;
        $taskHeader->assigned_user = $firstStepLog->actioning_officer;
        $taskHeader->save();

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
            'next_step' => $step->next_step,
            'previous_step' => $step->previous_step ?? '00',
            'remarks' => $comment
        ]);
    }

    /**
     * @param WorkflowTaskDetail $taskDetail
     * @param WorkflowTaskHeader $taskHeader
     * @param $comment
     * @param $action
     * @param $actionTaken
     * @param $currentStep
     * @return array
     */
    public function resubmitRequest(WorkflowTaskDetail $taskDetail,
                                    WorkflowTaskHeader $taskHeader,
                                                       $comment,
                                                       $action,
                                                       $actionTaken,
                                                       $currentStep
    ): array
    {

        $currentUser = auth()->user();

        $previousStepLog = WorkflowLog::where(
            'reference',
            QueryComparisonOperator::EQUALS,
            $taskDetail->reference
        )
            ->orderBy('id', 'desc')
            ->first();

        $taskDetail->current_step_id = $previousStepLog->step_id;
        $taskDetail->actioning_officer = $previousStepLog->actioning_officer;
        $taskDetail->save();

        Log::debug("Going Forward To Step $previousStepLog->step_id");

        $previousStep = WorkflowStep::where(
            'process_id',
            QueryComparisonOperator::EQUALS,
            $taskHeader->process_code
        )->where(
            'step_id',
            QueryComparisonOperator::EQUALS,
            $previousStepLog->step_id
        )->first();

        $taskHeader->url = $previousStep->action_page;
        $taskHeader->assigned_user = $previousStepLog->actioning_officer;
        $taskHeader->save();

        $this->createLog(
            $comment,
            $currentUser,
            $action,
            $actionTaken,
            StatusHelper::resubmitted(),
            $currentStep,
            $taskDetail->reference
        );

        return [$taskDetail->current_step_id, '0'];
    }

    public function getAllWorkflowTasks(): Collection
    {
        return DB::table('WFL_WORKFLOW_TASK task_header')
            ->leftJoin('SEC_USERS users',
                'task_header.created_by',
                QueryComparisonOperator::EQUALS,
                'users.id')
            ->leftJoin('SEC_USERS approvers',
                'task_header.assigned_user',
                QueryComparisonOperator::EQUALS,
                'approvers.staff_no')
            ->whereNull('task_header.date_ended')
            ->select(
                'task_header.*',
                'users.name as originator',
                'approvers.name as approver'
            )
            ->orderBy(
                'task_header.created_at',
                'desc')
            ->get();
    }

}
