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
    const Approve = 3;
    const Reject = 2;

    /**
     * Initialize Approval task
     * @param string $taskReference
     * @param int $processCode
     * @param int $action
     * @param string $comment
     * @param $currentUser
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
                                                   $currentUser,
                                                   $amount,
                                            string $short_description,
                                            string $long_description,
                                            string $assignTo = null
    ): WorkflowTaskDetail
    {

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
        $processFirstStep = WorkflowStep::where('process_id', '=', $processCode)
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
            throw new WorkflowTaskCreationFailedException("Could not Determine Next Step");
        }

        $userUnit = $this->getUserUnit($currentUser);

        // audit trail for submission of task
        WorkflowLog::create([
            'reference' => $taskReference,
            'step_id' => $processFirstStep->step_id,
            'actioning_officer' => $currentUser->staff_no,
            'action' => $action,
            'activity' => "Create Document",
            'status' => StatusHelper::submitted(),
            'action_date' => Carbon::now(),
            'next_step' => $stepAfterSubmission->step_id,
            'previous_step' => '00',
            'remarks' => $comment
        ]);

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
        WorkflowTaskHeader::Create([
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
                                   string $process_id,
                                   int    $action,
                                   string $actionTaken,
                                   string $comment
    ): array
    {

        Log::info('Processing Workflow Reference ' . $reference);
        Log::info('Workflow Process Code ' . $process_id);

        $currentUser = auth()->user();

        // get workflow process header for the task
        $taskHeader = WorkflowTaskHeader::where('reference', '=', trim($reference))
            ->where('process_code', '=', $process_id)
            ->first();

        // get workflow process detail
        $taskDetail = WorkflowTaskDetail::where('reference', '=', trim($reference))
            ->where('process_code', '=', $process_id)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($taskDetail)) {
            throw new WorkflowTaskCreationFailedException("Approval Process Details Not Found", 100);
        }

        if (empty($taskHeader)) {
            throw new WorkflowTaskCreationFailedException("Approval Process Heading Data Not Found", 100);
        }

        if (empty($taskDetail->current_step_id)) {
            throw new WorkflowTaskCreationFailedException("Approval Process Current State Data Is Missing", 101);
        }

        $processStatus = '';

        // always start at current position
        $currentStep = WorkflowStep::where('step_id', '=', $taskDetail->current_step_id)
            ->where('process_id', '=', $process_id)
            ->first();


        //update workflow log
        if (empty($currentStep)) {
            throw new WorkflowTaskCreationFailedException("Approval Process Current State Record Not Found", 102);
        }

        Log::info("Action Passed is " . $action);
        Log::info("Action Taken " . $actionTaken);

        $userUnit = $taskHeader->user_unit ?? 'G1500';

        $lastStep = $this->getApprovalLimit($userUnit, $taskHeader->amount);

        switch ($action) {
            case 1:
                // resubmission
                break;
            case self::Reject:
                DB::beginTransaction();
                WorkflowLog::create([
                    'remarks' => $comment,
                    'action_date' => Carbon::now(),
                    'actioning_officer' => $currentUser->staff_no,
                    'action' => $action,
                    'activity' => $actionTaken,
                    'status' => StatusHelper::rejected(),
                    'previous_step' => $currentStep->previous_step,
                    'step_id' => $currentStep->step_id,
                    'reference' => $reference
                ]);

                $taskHeader->date_ended = Carbon::now();
                $taskHeader->status = StatusHelper::rejected();
                $taskHeader->save();

                $taskDetail->date_ended = Carbon::now();
                $taskDetail->save();
                DB::commit();
                return [100, "0"];
            case self::Approve:
                // approved
                if ($currentStep->is_final_step
                    || $currentStep->is_final_step == '1'
                    || $currentStep->is_final_step == 1
                    || $currentStep == $lastStep) {
                    Log::info("Final Step Approving and Ending Process");

                    WorkflowLog::create([
                        'remarks' => $comment,
                        'action_date' => Carbon::now(),
                        'actioning_officer' => $currentUser->staff_no,
                        'action' => $action,
                        'activity' => $actionTaken,
                        'status' => StatusHelper::authorised(),
                        'previous_step' => $currentStep->previous_step,
                        'step_id' => $currentStep->step_id,
                        'reference' => $reference
                    ]);

                    $taskHeader->date_ended = Carbon::now();
                    $taskHeader->status = StatusHelper::approved();
                    $taskHeader->save();

                    $taskDetail->date_ended = Carbon::now();
                    $taskDetail->save();
                    //DB::commit();
                    return [100, '0'];
                }

                Log::info("Workflow Step Not Final ");
                // get step
                $nextStep = WorkflowStep::where('step_id', '=', $currentStep->next_step)
                    ->where('process_id', '=', $process_id)
                    ->first();

                Log::info("Next Step Determined As " . $nextStep->step_id);

                if (empty($nextStep)) {
                    throw new WorkflowTaskCreationFailedException("Approval Process Next State Record Not Found", 102);
                }

                // create partial authorisation log
                Log::info("Creating Approval Log ");
                WorkflowLog::create([
                    'remarks' => $comment,
                    'action_date' => Carbon::now(),
                    'actioning_officer' => $currentUser->staff_no,
                    'action' => $action,
                    'activity' => $actionTaken,
                    'status' => StatusHelper::partiallyAuthorised(),
                    'previous_step' => $currentStep->previous_step,
                    'step_id' => $currentStep->step_id,
                    'reference' => $reference
                ]);

                $taskDetail->current_step_id = $nextStep->step_id;
                //send notification to actioning officer

                //find current_user with role
                if ($nextStep->privilege != null) {
                    // find people with privilege
                    /*userRoles = _context . UserRoles
                        . include(usr => usr . User)
                    .Where(ur => ur . RoleId == nextStep . Role)
                    .ToList();*/
                }

                // is supervisor of
                $approvingOfficer = $this->getApprovingOfficer($currentUser);

                $assignToUser = $approvingOfficer;
                Log::info("Next Approver Determine as " . $assignToUser->staff_no);

                if ($assignToUser->staff_no != 0) {
                    $taskDetail->actioning_officer = $assignToUser->staff_no;
                }

                // save process and send notification
                $taskDetail->save();

                $taskHeader->assigned_user = $assignToUser->staff_no;
                $taskHeader->save();

                Log::info("Returning Next Step Id " . $nextStep->step_id);

                DB::commit();
                return [$nextStep->step_id, $assignToUser->staff_no];
            case 5:
            {
                //send back
                $previousStep = WorkflowStep::where('step_id', '=', $currentStep->previous_step)->first();

                $previousStepLog = WorkflowLog::where('reference', '=', $taskDetail->reference)
                    ->where('step_id', '=', $previousStep->step_id);

                if ($previousStepLog != null) {
                    $taskDetail->actioning_officer = $previousStepLog->actioning_officer;
                } else {
                    $taskDetail->actioning_officer = 54;
                }

                $taskDetail->current_step_id = $previousStep->step_id;

                //save process and send notification
                $taskDetail->save();
                //new notification

                self::closePreviousTasks($taskDetail);

                //"Fuel Requisition -"
                //self::createUserNotification($task_detail, "Task Sent Back", $previousStep->action_page ?? "");

                $taskDetail->save();
                return [$taskDetail, '0'];
            }
            case 7:
                $taskDetail->current_step_id = null;
                $taskDetail->actioning_officer = null;
                $taskDetail->save();

                self::closePreviousTasks($taskDetail);


                //process is finished
                return [$taskDetail, '0'];
            case 6:
                // Reject

                $verificationStep = WorkflowStep::where('step_id', '=', 2)->first();

                $verificationStepLog = WorkflowLog::
                where('reference', '=', $taskDetail->reference)
                    ->where('step_id', '=', $verificationStep->step_id)->first();

                if ($verificationStepLog != null) {
                    $taskDetail->ctioning_officer = $verificationStepLog->actioning_officer;
                } else {
                    $taskDetail->ctioningOfficer = 54;
                }

                $taskDetail->current_step_id = $verificationStep->step_id . ToString();
                //save process and send notification
                $taskDetail->save();
                //new notification

                $actionPage = $verificationStep->actionPage ?? "";

                self::closePreviousTasks($taskDetail);

                //self::createUserNotification($task_detail, "Task Rejected", $actionPage);

                $taskDetail->save();

                return [$taskDetail, '0'];

            default:
                $taskDetail->current_step_id = null;
                $taskDetail->actioning_officer = null;

                self::closePreviousTasks($taskDetail);

                $taskDetail->save();

                //process is finished
                return [$taskDetail, '0'];
        }

        DB::beginTransaction();
        WorkflowLog::create([
            'remarks' => $comment,
            'action_date' => Carbon::now(),
            'actioning_officer' => $currentUser->staff_no,
            'action' => $actionTaken,
            'status' => $processStatus,
            'next_step' => $currentStep->next_step,
            'previous_step' => $currentStep->previous_step,
            'step_id' => $currentStep->step_id,
            'reference' => $taskDetail->reference
        ]);
        DB::commit();

        return ["00", '0'];
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
     * @param User $currentUser
     * @return mixed
     * @throws WorkflowTaskCreationFailedException
     */
    public function getApprovingOfficer(User $currentUser): mixed
    {
        /****************************Determine User to assign task*************************************************/
        if (empty($currentUser->supervisor_code)) {
            throw new WorkflowTaskCreationFailedException("Supervisor Not Assigned Found");
        }

        $assignToUser = User::where('staff_no', '=', $currentUser->supervisor_code)
            //->where('con_st_code', 'ACT')
            ->first();

        if (empty($assignToUser)) {
            throw new WorkflowTaskCreationFailedException("Supervisor Is Not A Fleet Master User");
        }

        return $assignToUser;
    }

    private function createUserNotification(string $taskReference, int $actioningOfficer, string $title, string $actionPage, $longDescription): void
    {
        $currentUser = auth()->user();

        WorkflowTaskHeader::Create([
            'assigned_user' => $actioningOfficer->con_per_no,
            'subject' => $title . $taskReference,
            'status' => StatusHelper::new(),
            'url' => $actionPage,
            'reference' => $taskReference,
            'priority' => Priority::high(),
            'description' => '',
            'long_description' => $longDescription,
            'created_by' => $currentUser->id,
            'date_acted' => Carbon::now()
        ]);
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
        $existingNotifications = WorkflowTaskHeader::where('subject', 'LIKE', "%{$process->reference}%")->get();

        foreach ($existingNotifications as $existingNotification) {
            $existingNotification->status = StatusHelper::closed();
            $existingNotification->save();
        }

    }

    public function endProcess($reference): bool
    {
        //get workflow process
        $process = WorkflowTaskDetail::where('reference', '=', $reference);

        if ($process == null) return false;

        $process->current_step_id = null;
        $process->actioning_officer = null;
        $process->save();

        return true;
    }

    public
    function writeDocumentCreationWorkflowLogEntry
    (
        int $status,
            $requestId,
            $requestReference,
            $user,
            $formType,
            $remarks = "Raise Request"
    ): void
    {
        /*switch ($formType) {
            case config('constants.eforms_id.kilometer_allowance'):
                $remarks = 'Request for Kilometer Allowance';
                break;
            case config('constants.eforms_id.subsistence'):
                $remarks = 'Request for Subsistence Allowance';
                break;
            case config('constants.eforms_id.hotel_accommodation'):
                $remarks = 'Request for Hotel Accommodation';
                break;
            default:
                break;
        }

        EformApprovalsModel::Create([
            'profile' => $user->profile_id,
            'name' => $user->name,
            'staff_no' => $user->staff_no,
            'reason' => $remarks,
            'action' => "Create Document",
            'current_status_id' => $status,
            'action_status_id' => $status,
            'config_eform_id' => $formType,
            'eform_id' => $requestId,
            'eform_code' => $requestReference,
            'created_by' => $user->id,
        ]);*/
    }

    /**
     * Retrieves the code unit of the user
     * @throws WorkflowTaskCreationFailedException
     */
    private function getUserUnit(User $currentUser)
    {
        $ou = OrganizationalUnit::where('cc_code', '=', $currentUser->cc_code)
            ->where('bu_code', '=', $currentUser->bu_code)
            ->first();
        if (!$ou) {
            throw new WorkflowTaskCreationFailedException("User Unit Not Found");
        }

        return $ou->code_unit;
    }


}
