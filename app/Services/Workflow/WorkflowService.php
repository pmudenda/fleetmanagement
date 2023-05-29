<?php

namespace App\Services\Workflow;


use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\Priority;
use App\Helpers\StatusHelper;
use App\Models\reference\PHCMSEmployee;
use App\Models\Security\User;
use App\Models\Workflow\WorkflowActions;
use App\Models\Workflow\WorkflowLog;
use App\Models\Workflow\WorkflowProcess;
use App\Models\Workflow\WorkflowStep;
use App\Models\Workflow\WorkflowTaskDetail;
use App\Models\Workflow\WorkflowTaskHeader;
use Illuminate\Support\Carbon;

class WorkflowService
{
    /**
     * Initialize Approval task
     * @param string $taskReference
     * @param int $processCode
     * @param int $action
     * @param string $comment
     * @param User $currentUser
     * @return WorkflowTaskDetail
     * @throws WorkflowTaskCreationFailedException
     */
    public function startWorkflowProcess(string $taskReference,
                                         int    $processCode,
                                         int    $action,
                                         string $comment,
                                         User   $currentUser): WorkflowTaskDetail
    {

        $process = WorkflowProcess::where('process_code', $processCode)->first();

        if ($process == null) throw new WorkflowTaskCreationFailedException("Process not Found");

        //get the first step in this process
        $process_first_step = WorkflowStep::where('process_id', '=', $processCode)
            ->where('is_initial_step', true)
            ->where('is_initial_step', '=', 1)
            ->first();

        if ($process_first_step == null) throw new WorkflowTaskCreationFailedException("Could not Determine Initial Step");

        if ($process_first_step->next_step == null) throw new WorkflowTaskCreationFailedException("Could not Determine Next Step Id");

        $stepAfterSubmission = WorkflowStep::where('process_id', '=', $processCode)
            ->where('step_id', '=', $process_first_step->next_step)->first();

        if ($stepAfterSubmission == null) throw new WorkflowTaskCreationFailedException("Could not Determine Next Step");

        // create submission line
        WorkflowLog::create([
            'reference' => $taskReference,
            'step_id' => $process_first_step->step_id,
            'actioning_officer' => $currentUser->staff_no,
            'action' => WorkflowActions::Submit(),
            //'action' => "Create Document",
            'status' => StatusHelper::Submitted(),
            'action_date' => Carbon::now(),
            'next_step' => $stepAfterSubmission->step_id,
            'previous_step' => '00',
            'remarks' => $comment
        ]);


        /****************************** Determine User to assign task ******************************************/
        //find user role required on step after submission
        //$userRoles = [];//::where('RoleId', $stepAfterSubmission->PrivilegeId)->get();

        $assignToUser = PHCMSEmployee::where('con_per_no', '=', $currentUser->supervisor_code)->first();
        $smallestNumberOfTasks = 0;

        //find user with the least number of tasks and assign this task
        //$userId = $assignToUser->con_per_no ?? 0;
        /*foreach ($userRoles as $userRole) {
            //
            $actioningTasks = WorkflowTaskDetail::where('actioning_officer', $userRole->UserId)->whereNotNull('current_step_id')
                ->Count();

            if ($userId == 0) {
                // first run
                $smallestNumberOfTasks = $actioningTasks;
                $userId = $userRole->UserId;
                continue;
            }

            if ($actioningTasks < $smallestNumberOfTasks) {
                $smallestNumberOfTasks = $actioningTasks;
                $userId = $userRole->UserId;
            }
        }*/
        /****************************Determine User to assign task*************************************************/

        //$assignToUser->UserId = $userId;
        $actionPage = $stepAfterSubmission->action_page;

        //'date_acted'
        WorkflowTaskHeader::Create([
            'assigned_user' => $assignToUser->con_per_no,
            'subject' => "You have received a fuel requisition approval task -" . $taskReference,
            //'message' => ,
            'status' => StatusHelper::new(),
            'url' => $actionPage,
            'reference' => $taskReference,
            'priority' => Priority::high(),
            'description' => '',
            'sender' => 'SYSTEM',
            'created_by' => $currentUser->id,
            'date_acted' => Carbon::now()
        ]);

        $newProcess = WorkflowTaskDetail::create([
            'user_id'=> $currentUser->staff_no,
            'actioning_officer' => $assignToUser->con_per_no,
            'current_step_id' => $stepAfterSubmission->step_id,
            'status' => StatusHelper::new(),
            'step_after_submission' => $actionPage,
            'reference' => $taskReference,
            'process_code' => $processCode,
            'date_started' => Carbon::now(),
            /*'date_ended'*/
        ]);

        /*self::createUserNotification($taskReference,
            $newProcess->ActioningOfficer ?? 0,
            "New Incident Request Task", $actionPage);*/

        return $newProcess;
    }


    private function createUserNotification(string $taskReference, int $actioningOfficer, string $title, string $actionPage): void
    {
        $currentUser = auth()->user();

        WorkflowTaskHeader::Create([
            'assigned_user' => $actioningOfficer->con_per_no,
            'subject' => $title . $taskReference,
            'message' => 'You have received an approval task',
            'status' => StatusHelper::new(),
            'url' => $actionPage,
            'reference' => $taskReference,
            'priority' => Priority::high(),
            'description' => '',
            'sender' => 'SYSTEM',
            'created_by' => $currentUser->id,
            'date_acted' => Carbon::now()
        ]);
    }

    public function cancelProcessTask($req_no)
    {
    }


    private function create_UserNotification(WorkflowTaskDetail $workflowTask, $title, $actionPage): void
    {
        if ($workflowTask->ActioningOfficer == null) return;

        $notification = WorkflowTaskHeader::create
        ([
            /*Sender = "System",
            AssignedUser = (int)workflowTask . ActioningOfficer,
            Subject = $"{title} - " + workflowTask . Reference,
            Message = " You have received a workflow task  &nbsp; &nbsp;<a style='padding-top: 0.1em;padding-bottom: 0.1em;' class='btn btn-primary btn-md' href='" + actionPage + "?refNo=" +
             workflowTask . Reference +
             "'> <i class='fa fa-folder-open-o' aria-hidden='true'></i> Open Task</a>",
            Status = "SENT",
            DateReceived = Carbon::now()*/
        ]);
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


    /**
     * @throws WorkflowTaskCreationFailedException
     */
    public function moveWorkflowProcess(string $reference, int $action, string $actionTaken, string $comment, string $processStatus): WorkflowTaskDetail
    {
        // get workflow process

        $task_detail = WorkflowTaskDetail::where('reference', '=', $reference)->first();

        if (empty($task_detail)) return new WorkflowTaskDetail();

        if (empty($task_detail->current_step_id)) {
            $task_detail->current_step_id = null;
            $task_detail->actioning_officer = null;
            $task_detail->save();

            self::closePreviousTasks($task_detail);

            return $task_detail;
        }

        // get step process is at
        $stepId = (int)($task_detail->current_step_id);

        $current_step = WorkflowStep::where('step_id', '=', $stepId)->first();

        //update workflow log
        /*if (empty($task_detail->current_step_id)) {
            throw new WorkflowTaskCreationFailedException("Could not determine next step");
        }*/

        WorkflowLog::create([
            'remarks' => $comment,
            'action_date' => Carbon::now(),
            'actioning_officer' => auth()->user()->staff_no,
            'action' => $actionTaken,
            'status' => $processStatus,
            'next_step' => $current_step->next_step,
            'previous_step' => $current_step->previous_step,
            'step_id' => $current_step->step_id,
            'reference' => $task_detail->reference
        ]);

        switch ($action) {

            // Verify = 2,
            case 2:
                //   Recommend=3,
            case 3:
                // Approve = 4,
            case 4:
                // Submit = 1,
            case 1:
            {
                //check if the current step is final step .CurrentStep.IsFinalStep == 1
                if ((bool)$current_step == $task_detail->is_final_step) {
                    $task_detail->current_step_id = null;
                    $task_detail->actioning_officer = null;
                    $task_detail->save();

                    closePreviousTasks($task_detail);

                    //process is finished
                    return $task_detail;
                }

                //get next step

                $next_step = WorkflowStep::where('step_id', '=', $current_step->next_step);

                if ($next_step == null) {
                    $task_detail->current_step_id = null;
                    $task_detail->actionong_officer = null;
                    $task_detail->save();

                    self::closePreviousTasks($task_detail);

                    return $task_detail;
                }

                $task_detail->current_step_id = $next_step->step_id;

                //send notification to actioning officer

                //find user with role

                $userRoles = [];

                if ($next_step->privilege != null) {
                    /*userRoles = _context . UserRoles
                        . include(usr => usr . User)
                    .Where(ur => ur . RoleId == nextStep . Role)
                    .ToList();*/
                }


                $assign_to_user = new User([0]);

                $smalletNumberOfTasks = 0;
                //find user with the least number of tasks and assign this task
                $userId = 0;
                /*foreach ($userRoles as $userRole) {
                    //
                     $actioningTasks = WorkflowTaskDetail::where(
                         'actioning_officer','=' , $userRole->user_id && $next_step->current_step_id != null)
                             .count();

                         if ($userId == 0) {
                             // first run
                             smalletNumberOfTasks = actioningTasks;
                             userId = (int)userRole . UserId;
                             continue;
                         }

                         if (actioningTasks < smalletNumberOfTasks) {
                             smalletNumberOfTasks = actioningTasks;
                             userId = (int)userRole . UserId;
                         }
                     }*/


                $assign_to_user->UserId = $userId;

                if ($assign_to_user->user_id != 0) {
                    $task_detail->actioning_officer = $assign_to_user->user_id;
                }

                // save process and send notification
                $task_detail->save();
                // new notification

                self::closePreviousTasks($task_detail);

                // next step is present
                $finalApproval = (bool)$next_step->is_final_step;

                if ($finalApproval) {
                    // final approval
                    //_newConnectionService . Close($task_detail->reference);

                    //CreateUserNotification(taskDetail, "New Connection Request Work Task", nextStep?.ActionPage);
                } else {
                    self::createUserNotification(
                        $task_detail, "New Connection Request Work Task",
                        $next_step->action_page
                    );
                }

                return $task_detail;
            }
            // SendBack = 5,
            case 5:
            {
                //send back

                $previousStep = WorkflowStep::where('step_id', '=', $current_step->previous_step)->first();

                $previousStepLog = WorkflowLog::where('reference', '=', $task_detail->reference)
                    ->where('step_id', '=', $previousStep->step_id);

                if ($previousStepLog != null) {
                    $task_detail->actioning_officer = $previousStepLog->actioning_officer;
                } else {
                    $task_detail->actioning_officer = 54;
                }

                $task_detail->current_step_id = $previousStep->step_id;

                //save process and send notification
                $task_detail->save();
                //new notification

                self::closePreviousTasks($task_detail);

                //"Fuel Requisition -"
                self::createUserNotification($task_detail, "Task Sent Back", $previousStep->action_page ?? "");

                $task_detail->save();

                return $task_detail;
            }
            //  Pay=7
            case 7:
                $task_detail->current_step_id = null;
                $task_detail->ctioning_officer = null;
                $task_detail->save();

                self::closePreviousTasks($task_detail);


                //process is finished
                return $task_detail;
            // Reject= 6,
            case 6:
                // Reject

                $verificationStep = WorkflowStep::where('step_id', '=', 2)->first();

                $verificationStepLog = WorkflowLog::
                where('reference', '=', $task_detail->reference)
                    ->where('step_id', '=', $verificationStep->step_id)->first();

                if ($verificationStepLog != null) {
                    $task_detail->ctioning_officer = $verificationStepLog->actioning_officer;
                } else {
                    $task_detail->ctioningOfficer = 54;
                }

                $task_detail->current_step_id = $verificationStep->step_id . ToString();

                //save process and send notification
                $task_detail->save();
                //new notification

                $actionPage = $verificationStep->actionPage ?? "";

                self::closePreviousTasks($task_detail);

                self::createUserNotification($task_detail, "Task Rejected", $actionPage);

                $task_detail->save();

                return $task_detail;

            default:
                $task_detail->current_step_id = null;
                $task_detail->actioning_officer = null;

                self::closePreviousTasks($task_detail);

                $task_detail->save();

                //process is finished
                return $task_detail;
        }
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


}
