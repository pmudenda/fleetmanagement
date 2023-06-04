<?php

namespace App\Services\Workflow;


use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\Priority;
use App\Helpers\StatusHelper;
use App\Models\reference\PHCMSEmployee;
use App\Models\Security\User;
use App\Models\Workflow\WorkflowLog;
use App\Models\Workflow\WorkflowProcess;
use App\Models\Workflow\WorkflowStep;
use App\Models\Workflow\WorkflowTaskDetail;
use App\Models\Workflow\WorkflowTaskHeader;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    public function initiateWorkflowProcess(string $taskReference,
                                            int    $processCode,
                                            int    $action,
                                            string $comment,
                                            User   $currentUser,
                                                   $amount
    ): WorkflowTaskDetail
    {

        Log::info('Reference ' . $taskReference . ' Process Code ' . $processCode . ' Action ' . $action . ' Comment ' . $comment . ' Amount ' . $amount);

        $process = WorkflowProcess::where('process_code', $processCode)->first();

        if (empty($process)) throw new WorkflowTaskCreationFailedException("Process not Found");

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
            'action' => $action,
            'activity' => "Create Document",
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
            'subject' => "Approval Task -" . $taskReference,
            'status' => StatusHelper::new(),
            'url' => $actionPage,
            'reference' => $taskReference,
            'priority' => Priority::high(),
            'description' => 'You have received a fuel requisition approval task ',
            'sender' => 'SYSTEM',
            'created_by' => $currentUser->id,
            'date_acted' => Carbon::now(),
            'process_code' => $processCode,
            'amount' => $amount
        ]);

        /*self::createUserNotification($taskReference,
                    $newProcess->ActioningOfficer ?? 0,
                    "New Incident Request Task", $actionPage);*/

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
            /*'date_ended'*/
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
    ): int
    {
        $current_user = auth()->user();
        // get workflow process header
        $task_header = WorkflowTaskHeader::where('reference', '=', $reference)
            ->where('process_code', '=', $process_id)
            ->first();

        // get workflow process detail
        $task_detail = WorkflowTaskDetail::where('reference', '=', $reference)
            ->where('process_code', '=', $process_id)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($task_detail) || empty($task_header)) throw new WorkflowTaskCreationFailedException("Approval Process Data Not Found", 100);

        if (empty($task_detail->current_step_id)) {
            throw new WorkflowTaskCreationFailedException("Approval Process Current State Data Is Missing", 101);
        }

        $processStatus = '';


        // always start at current position
        $current_step = WorkflowStep::where('step_id', '=', $task_detail->current_step_id)
            ->where('process_id', '=', $process_id)
            ->first();


        //update workflow log
        if (empty($current_step)) {
            throw new WorkflowTaskCreationFailedException("Approval Process Current State Record Not Found", 102);
        }

        switch ($action) {
            case 1:
                // resubmission
                break;
            case 2:
                break;
            case 3: // approved
                //check if the current step is final step .CurrentStep.IsFinalStep == 1
                if ($current_step->is_final_step || $current_step->is_final_step == '1' || $current_step->is_final_step == 1) {
                    Log::info("Approving and Ending Process");

                    WorkflowLog::create([
                        'remarks' => $comment,
                        'action_date' => Carbon::now(),
                        'actioning_officer' => $current_user->staff_no,
                        'action' => $action,
                        'activity' => $actionTaken,
                        'status' => StatusHelper::authorised(),
                        'previous_step' => $current_step->previous_step,
                        'step_id' => $current_step->step_id,
                        'reference' => $reference
                    ]);

                    $task_header->date_ended = Carbon::now();
                    $task_header->status = StatusHelper::approved();
                    $task_header->save();

                    $task_detail->date_ended = Carbon::now();
                    $task_detail->save();

                    /*
                     WorkflowTaskDetail::create([
                        'reference' => $reference,
                        'process_code' => $process_id,
                        'user_id' => $current_user->id,
                        'current_step_id' => $current_step->step_id,
                        'actioning_officer' => $current_user->staff_no,
                        'status' => StatusHelper::new(),
                        'step_after_submission' => $current_step->action_page,
                        'date_started' => Carbon::now(),
                        'created_by' => $current_user->staff_no,
                        'date_ended' => Carbon::now(),
                    ]);
                    */

                    return 100;
                }

                $next_step = WorkflowStep::where('step_id', '=', $current_step->next_step)
                    ->where('process_id', '=', $process_id)
                    ->first();


                if (empty($next_step)) {
                    throw new WorkflowTaskCreationFailedException("Approval Process Next State Record Not Found", 102);
                }
                //get next step

                /*if ($next_step == null) {
                    $task_detail->current_step_id = null;
                    $task_detail->actionong_officer = null;
                    $task_detail->save();

                    self::closePreviousTasks($task_detail);

                    return $task_detail;
                }*/

                //$task_detail->current_step_id = $next_step->step_id;

                //send notification to actioning officer

                //find current_user with role

                $userRoles = [];

                /*if ($next_step->privilege != null) {
                    userRoles = _context . UserRoles
                        . include(usr => usr . User)
                    .Where(ur => ur . RoleId == nextStep . Role)
                    .ToList();
                    }*/

                //$assign_to_user = new User([0]);

                $smalletNumberOfTasks = 0;
                //find current_user with the least number of tasks and assign this task
                //$userId = 0;
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

                /*  $assign_to_user->UserId = $userId;

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
                  }*/

                return 00;
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


        WorkflowLog::create([
            'remarks' => $comment,
            'action_date' => Carbon::now(),
            'actioning_officer' => $current_user->staff_no,
            'action' => $actionTaken,
            'status' => $processStatus,
            'next_step' => $current_step->next_step,
            'previous_step' => $current_step->previous_step,
            'step_id' => $current_step->step_id,
            'reference' => $task_detail->reference
        ]);

        return $next_step->step_id;
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

    public function cancelProcessTask($req_no, $process_id)
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

        if($task_header){
            $task_header->date_ended = Carbon::now();
            $task_header->status = StatusHelper::cancelled();
            $task_header->save();
        }

        if($task_detail){
            $task_detail->date_ended = Carbon::now();
            $task_detail->save();
        }

        DB::commit();
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
