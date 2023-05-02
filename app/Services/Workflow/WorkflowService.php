<?php

namespace App\Services\Workflow;



use App\Helpers\StatusHelper;
use App\Models\Security\User;
use App\Models\Workflow\WorkflowActions;
use App\Models\Workflow\WorkflowLog;
use App\Models\Workflow\WorkflowProcess;
use App\Models\Workflow\WorkflowStep;
use App\Models\Workflow\WorkflowTaskDetail;

class WorkflowService
{
    public  function startWorkflowProcess(string $taskReference, int $processCode, int $action, string $comment, $currentUser): WorkflowTaskDetail
    {
        $process = WorkflowProcess::where('ProcessCode', $processCode)->first();

        if ($process == null) return new WorkflowTaskDetail();

        //get the first step in this process
        $firstStep = WorkflowStep::where('ProcessId', '=', $processCode)
            ->where('IsInitialStep', true)->first();

        if ($firstStep == null) return new WorkflowTaskDetail();

        if ($firstStep->NextStep == null) return new WorkflowTaskDetail();

        $stepAfterSubmission = WorkflowStep::
        where('ProcessId', '=', $processCode)
            ->where('StepId', '=', $firstStep->NextStep)->first();

        if ($stepAfterSubmission == null) return new WorkflowTaskDetail(['id' => 0]);

        // create submission line
        $submissionStep = WorkflowLog::Create([
            'Reference' => $taskReference,
            'StepId' => $firstStep->StepId,
            'ActioningOfficer' => $currentUser->UserId,
            'Action' => WorkflowActions::Submit(),
            'Status' => StatusHelper::Submitted(),
            'ActionDate' => date('Y/m/d'),
            'NextStep' => $stepAfterSubmission->StepId,
            'Remarks' => 'Submission Of New Connection Request'
        ]);

        //find user role required on step after submission
        $userRoles = [];//::where('RoleId', $stepAfterSubmission->PrivilegeId)->get();

        $assignToUser = new User(['UserId' => 0]);//{};
        $smallestNumberOfTasks = 0;
        //find user with the least number of tasks and assign this task
        $userId = 0;
        foreach ($userRoles as $userRole) {
            //
            $actioningTasks = WorkflowTaskDetail::where('ActioningOfficer', $userRole->UserId)->whereNotNull('CurrentStepId')
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
        }

        $assignToUser->UserId = $userId;
        $actionPage = $stepAfterSubmission->ActionPage;

        $newProcess = WorkflowTaskDetail::Create([
            'ActioningOfficer' => $assignToUser->UserId,
            'CurrentStepId' => $stepAfterSubmission->StepId,
            'Status' => StatusHelper::PendingVerification(),
            'stepAfterSubmission' => $actionPage,
            'Reference' => $taskReference,
            'ProcessCode' => $processCode,
            'DateStarted' => date('Y/m/d'),
        ]);



        self::createUserNotification($taskReference, $newProcess->ActioningOfficer ?? 0, "New Incident Request Task", $actionPage);

        return $newProcess;
    }


    private function createUserNotification(string $taskReference, int $actioningOfficer, string $title, string $actionPage): void
    {
        WorkflowTask::Create([
            'AssignedUser' => $actioningOfficer,
            'Subject' => $title . "-" . $taskReference,
            'Message' => 'You have received a workflow task',
            'Status' => 'SENT',
            'url'=> $actionPage,
            'reference'=> $taskReference,
            'priority'=> Priority::high(),
            'description'=> '',
            'DateReceived' => date('Y/m/d')
        ]);
    }
    /*
         private void CreateUserNotification(WorkflowTaskDetail workflowTask, string title, string actionPage)
         {
             if (workflowTask . ActioningOfficer == null) return;
             var
             notification = new WorkflowTask
             {
             Sender = "System",
                 AssignedUser = (int)workflowTask . ActioningOfficer,
                 Subject = $"{title} - " + workflowTask . Reference,
                 Message = " You have received a workflow task  &nbsp; &nbsp;<a style='padding-top: 0.1em;padding-bottom: 0.1em;' class='btn btn-primary btn-md' href='" + actionPage + "?refNo=" +
                     workflowTask . Reference +
                     "'> <i class='fa fa-folder-open-o' aria-hidden='true'></i> Open Task</a>",
                 Status = "SENT",



    DateReceived = DateTime . Now
             };

             _context . WorkflowTasks . Add(notification);
         }

         private void ClosePreviousTasks(WorkflowTaskDetail process)
         {
             var
             existingNotifications = _context . WorkflowTasks
                 . Where(notification => notification . Subject . EndsWith(process . Reference))
                 .ToList();

             foreach (var existingNotification in existingNotifications)
             {
                 existingNotification . Status = Status . CLOSED . ToString();
             }

             _context . UpdateRange(existingNotifications);
         }


         public bool EndProcess(string reference)
         {
             //get workflow process
             WorkflowTaskDetail process = _context . WorkflowTaskDetails
             . FirstOrDefault(s => s . Reference . Equals(reference));

             if (process == null) return false;

             process . CurrentStepId = null;
             process . ActioningOfficer = null;
             _context . WorkflowTaskDetails . Update(process);
             _context . SaveChanges();

             //process is finished
             return true;
         }


         public WorkflowTaskDetail MoveWorkflowProcess(string reference, int action, string actionTaken, string comment, string processStatus)
         {
             // get workflow process
             var
             taskDetail = _context . WorkflowTaskDetails
                 . FirstOrDefault(s => s . Reference == reference);

             if (taskDetail == null) return new WorkflowTaskDetail();

             //if step where taskcan no be defined, close task
             if (string . IsNullOrWhiteSpace(taskDetail . CurrentStepId)) {
                 taskDetail . CurrentStepId = null;
                 taskDetail . ActioningOfficer = null;
                 _context . WorkflowTaskDetails . Update(taskDetail);

                 ClosePreviousTasks(taskDetail);

                 _context . SaveChanges();
                 //process is finished
                 return taskDetail;
             }

             // get step process is at
             int stepId = Convert . ToInt32(taskDetail . CurrentStepId);

             WorkflowStep currentStep = _context . WorkflowSteps . FirstOrDefault(step => step . StepId == stepId);

             //update workflow log
             if (taskDetail . CurrentStepId != null) {
                 var
                 log = new WorkflowLog
                 {
                 Remarks = comment,
                     ActionDate = DateTime . Now,
                     ActioningOfficer = _userService . GetCurrentUser() ?.UserId,
                     Action = actionTaken,
                     Status = processStatus,
                     NextStep = currentStep . NextStep,
                     //PreviousStep = currentStep.PreviousStep,
                     StepId = currentStep . StepId,
                     Reference = taskDetail . Reference
                 };

                 _context . WorkflowLogs . Add(log);
             }

             switch (action) {

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
                     if ((bool)currentStep . IsFinalStep) {
                         taskDetail . CurrentStepId = null;
                         taskDetail . ActioningOfficer = null;
                         _context . WorkflowTaskDetails . Update(taskDetail);

                         ClosePreviousTasks(taskDetail);

                         _context . SaveChanges();

                         //process is finished
                         return taskDetail;
                     }

                     //get next step
                     var
                     nextStep =
                         _context . WorkflowSteps . FirstOrDefault(step => step . StepId == currentStep . NextStep);

                         if (nextStep == null) {
                             taskDetail . CurrentStepId = null;
                             taskDetail . ActioningOfficer = null;
                             _context . WorkflowTaskDetails . Update(taskDetail);

                             ClosePreviousTasks(taskDetail);

                             _context . SaveChanges();
                             //process is finished

                             return taskDetail;
                         }

                         taskDetail . CurrentStepId = nextStep . StepId . ToString();

                         //send notification to actioning officer

                         //find user with role
                         var userRoles = new list<UserRole > ();

                         if (nextStep . Role != null) {
                             userRoles = _context . UserRoles
                                 . include(usr => usr . User)
                             .Where(ur => ur . RoleId == nextStep . Role)
                             .ToList();
                         }


                         var assignToUser = new User() {
                     UserId = 0 };

                         int smalletNumberOfTasks = 0;
                         //find user with least number of tasks and assign this task
                         int userId = 0;
                         foreach (UserRole userRole in userRoles)
                         {
                             //
                             int actioningTasks = _context . WorkflowTaskDetails
                             . Where(a => a . ActioningOfficer == userRole . UserId && a . CurrentStepId != null)
                                 .Count();

                             if (userId == 0) {
                                 // first run
                                 smalletNumberOfTasks = actioningTasks;
                                 userId = (int)userRole . UserId;
                                 continue;
                             }

                             if (actioningTasks < smalletNumberOfTasks) {
                                 smalletNumberOfTasks = actioningTasks;
                                 userId = (int)userRole . UserId;
                             }
                         }


                         assignToUser . UserId = userId;

                         if (assignToUser ?.UserId != 0)
                         {
                             taskDetail . ActioningOfficer = assignToUser . UserId;
                         }

                         // save process and send notification
                         _context . WorkflowTaskDetails . Update(taskDetail);
                         // new notification

                         ClosePreviousTasks(taskDetail);

                         // next step is present
                         var finalApproval = (bool)nextStep . IsFinalStep;

                         if (finalApproval) {
                             // final approval
                             _newConnectionService . Close(taskDetail . Reference);
                             _context . SaveChanges();
                             //CreateUserNotification(taskDetail, "New Connection Request Work Task", nextStep?.ActionPage);
                         } else {
                             CreateUserNotification(taskDetail, "New Connection Request Work Task", nextStep ?.ActionPage);
                             _context . SaveChanges();
                         }

                         return taskDetail;
                     }
                 // SendBack = 5,
                 case 5:
                 {
                     //send back
                     var
                     previousStep =
                         _context . WorkflowSteps . FirstOrDefault(step => step . StepId == currentStep . PreviousStep);

                         var previousStepLog = _context . WorkflowLogs
                     . FirstOrDefault(l => l . Reference == taskDetail . Reference && l . StepId == previousStep . StepId);

                         if (previousStepLog != null) {
                             taskDetail . ActioningOfficer = previousStepLog . ActioningOfficer;
                         } else {
                             taskDetail . ActioningOfficer = 54;
                         }

                         taskDetail . CurrentStepId = previousStep . StepId . ToString();

                         //save process and send notification
                         _context . WorkflowTaskDetails . Update(taskDetail);
                         //new notification

                         ClosePreviousTasks(taskDetail);

                         CreateUserNotification(taskDetail, "Task Sent Back", previousStep ?.ActionPage ?? "");

                         _context . SaveChanges();

                         return taskDetail;
                     }
                 //  Pay=7
                 case 7:
                     taskDetail . CurrentStepId = null;
                     taskDetail . ActioningOfficer = null;
                     _context . WorkflowTaskDetails . Update(taskDetail);

                     ClosePreviousTasks(taskDetail);

                     _context . SaveChanges();

                     //process is finished
                     return taskDetail;
                 // Reject= 6,
                 case 6:
                     // Reject
                     var
                     verificationStep =
                         _context . WorkflowSteps . FirstOrDefault(step => step . StepId == 2);

                     var verificationStepLog = _context . WorkflowLogs
                     . FirstOrDefault(l => l . Reference == taskDetail . Reference && l . StepId == verificationStep . StepId);

                     if (verificationStepLog != null) {
                         taskDetail . ActioningOfficer = verificationStepLog . ActioningOfficer;
                     } else {
                         taskDetail . ActioningOfficer = 54;
                     }

                     taskDetail . CurrentStepId = verificationStep . StepId . ToString();

                     //save process and send notification
                     _context . WorkflowTaskDetails . Update(taskDetail);
                     //new notification

                     var actionPage = verificationStep ?.ActionPage ?? "";

                     ClosePreviousTasks(taskDetail);

                     CreateUserNotification(taskDetail, "Task Rejected", actionPage);

                     _context . SaveChanges();

                     // send notfication to customer


                     return taskDetail;

                 default:
                     taskDetail . CurrentStepId = null;
                     taskDetail . ActioningOfficer = null;
                     _context . WorkflowTaskDetails . Update(taskDetail);

                     ClosePreviousTasks(taskDetail);

                     _context . SaveChanges();

                     //process is finished
                     return taskDetail;
             }
         }*/



    public static function determineApplicationStage($user): int
    {
        return self::determineApplicationInitialStage($user);
    }

    private static function determineApplicationInitialStage($user): int
    {
        $user->load('grade');
        $grade = $user->grade;

        $gradeStatusMapCollection = Collect([
            ['gradeName' => config('grades.M0'), 'status' => Status::directorApproved()],
            ['gradeName' => config('grades.ML0'), 'status' => Status::directorApproved()],
            ['gradeName' => config('grades.ML1'), 'status' => Status::directorApproved()],
            ['gradeName' => config('grades.M1'), 'status' => Status::directorApproved()],
            ['gradeName' => config('grades.ML2'), 'status' => Status::directorApproved()],
            ['gradeName' => config('grades.M2'), 'status' => Status::directorApproved()],
            ['gradeName' => config('grades.ML3'), 'status' => Status::seniorManagerApproved()],
            ['gradeName' => config('grades.M3'), 'status' => Status::seniorManagerApproved()],
            ['gradeName' => config('grades.ML4'), 'status' => Status::headOfDepartmentApproved()],
            ['gradeName' => config('grades.M4'), 'status' => Status::headOfDepartmentApproved()],
        ]);

        if (!empty($gradeStatusMapCollection->firstWhere('gradeName', '=', $grade->name))) {
            $gradeArray = $gradeStatusMapCollection->firstWhere('gradeName', '=', $grade->name);
            return (((object)$gradeArray)->status);
        }

        //$hodAssignedProfile =  $user->user_profile()->where('profile_id', config('user-profiles.HeadOfDepartment'));
        // check if user has hod profile
        if (Authorise::hasHeadOfDepartmentRole($user)) {
            return Status::headOfDepartmentApproved();
        }

        return Status::newApplication();
    }

    public static function writeDocumentCreationWorkflowLogEntry
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

    public static function createSystemWorkflowApprovalLogEntry
    (
        int $status,
            $requestId,
            $requestReference,
            $user,
            $formType,
            $remarks = "Raise Request"
    ): void
    {
        /*$iterations = 1;

        $statusList = [""];
        $remarks = ['Subsistence Raised by User With HOD/Senior Manager Profile or User Invited By HOD'];
        if (Status::directorApproved() == $status) {
            //if ($status === config('eform_status.director_approved')) {
            $statusList = [
                config('eform_status.hod_approved'),
                config('eform_status.senior_mgr_approved'),
                config('eform_status.director_approved')
            ];
            $remarks = [
                'Subsistence Raised by User With HOD/Senior Manager Profile or User Invited By HOD',
                'Subsistence Raised by User With SENIOR MANAGER Profile',
                'Subsistence Raised by User With DIRECTOR Profile'
            ];
            $iterations = 3;
        }

        if ($status === Status::seniorManagerApproved()) {
            $statusList = [
                config('eform_status.hod_approved'),
                config('eform_status.senior_mgr_approved')
            ];
            $remarks = [
                'Subsistence Raised by User With HOD/Senior Manager Profile or User Invited By HOD',
                'Subsistence Raised by User With SENIOR MANAGER Profile'
            ];
            $iterations = 2;
        }

        if ($status === config('eform_status.hod_approved')) {
            $statusList = [config('eform_status.hod_approved')];
        }

        if (config('eform_status.new_application') == $status) {
            return;
        }

        for ($i = 0; $i < $iterations; $i++) {
            $statusFrom = $i == 0 ? $status : $statusList[$i];
            EformApprovalsModel::Create([
                'profile' => $user->profile_id,
                'name' => 'System',
                'staff_no' => 'N/A',
                'reason' => "System Auto Approve: " . $remarks[$i],
                'action' => "Approved",
                'current_status_id' => $statusFrom,
                'action_status_id' => $statusList[$i],
                'config_eform_id' => $formType,
                'eform_id' => $requestId,
                'eform_code' => $requestReference,
                'created_by' => $user->id,
            ]);
        }*/

    }

    public static function determineSubsistenceInitialApplicationStage($user, $hodUnit, $costCenterUserUnitCode): array
    {
        $departmental = false;
        $status = self::determineApplicationInitialStage($user);

        /*if (($user->user_unit->hod_unit == $hodUnit)
            || ($user->user_unit->user_unit_code == $hodUnit)
            || ($user->user_unit->user_unit_code == $costCenterUserUnitCode)) {
            $departmental = true;
        }

        // determine status by who invited the claimant
        if (Status::newApplication() == $status
            && ($user->user_unit->hod_unit == $hodUnit || $user->user_unit->user_unit_code == $hodUnit)) {
            $status = Status::headOfDepartmentApproved();
        }*/

        return array($status, $departmental);
    }


    public static function createWorkflowApprovalLogEntry(
        $user,
        $remarks,
        $action,
        $currentStatus,
        $formId,
        $newStatus,
        $eformId
    ): void
    {
        /* EformApprovalsModel::create(
             [
                 'profile' => $user->profile_id,
                 'title' => $user->profile_id,
                 'name' => $user->name,
                 'staff_no' => $user->staff_no,
                 'reason' => $remarks,
                 'action' => $action,
                 'current_status_id' => $currentStatus,
                 'action_status_id' => $newStatus,
                 'config_eform_id' => $eformId,
                 'eform_id' => $formId,
                 'created_by' => $user->id,
             ]
         );*/
    }

    /**
     * @param $action
     * @param $level
     * @return array
     */
    public static function determineNextStatus($action, $level): array
    {
        $insertComment = true;
        $newStatus = null;
        /*switch ($action) {
            case config('constants.approval.cancelled'):
                $newStatus = Status::cancelled();
                break;
            case config('constants.approval.reject'):
                $newStatus = Status::rejected();
                break;
            case config('constants.approval.approve'):
                $newStatus = self::requestApprovalMatched($level, $newStatus);
                break;
            case config('constants.approval.queried'):
                $newStatus = Status::queried();
                break;
            case config('constants.approval.resolve'):
                if ($level === self::EXPENDITURE_ON_QUERY_RESOLUTION) {
                    $newStatus = Status::awaitAudit();
                } else {
                    $newStatus = Status::closed();
                }
                break;
            default:
                $newStatus = self::noActionMatched($level);
                $insertComment = false;
                break;
        }*/
        return array($newStatus, $insertComment);
    }

    /**
     * @param $level
     * @return int
     */
    public static function requestApprovalMatched($level): int
    {
        $status = 0;
        /*      if ($level === self::SENIOR_MANAGER) {
                  $status = Status::seniorManagerApproved();
              } elseif ($level === self::TRANSPORT) {
                  $status = Status::transportOfficerApproved();
              } elseif ($level === self::HEAD_OF_DEPARTMENT) {
                  $status = Status::headOfDepartmentApproved();
              } elseif ($level === self::HUMAN_CAPITAL) {
                  $status = Status::humanCapitalApproved();
              } elseif ($level === self::DIRECTOR) {
                  $status = Status::closed();
              } elseif ($level === self::CHIEF_ACCOUNTANT) {
                  $status = Status::chiefAccountantApproved();
              } elseif ($level === self::EXPENDITURE_ACCOUNTANT) {
                  $status = Status::exported();
              } elseif ($level === self::FUNDS_DISBURSEMENT) {
                  $status = Status::fundsDisbursed();
              } elseif ($level === self::FUNDS_ACKNOWLEDGMENT) {
                  $status = Status::destinationApproval();
              } elseif ($level === self::SECURITY) {
                  $status = Status::securityApproved();
              } elseif ($level === self::RECEIPT_APPROVAL) {
                  $status = Status::receiptApproved();
              } elseif (
                  $level === self::AUDITOR
                  ||
                  $level === self::QUERY_RESOLUTION
                  ||
                  $level === self::EXPENDITURE_ON_QUERY_RESOLUTION
                  ||
                  $level === self::POST_AUDIT
              ) {
                  $status = Status::audited();
              } elseif ($level === self::DESTINATION_APPROVAL) {
                  $status = Status::awaitAudit();
              }*/
        return $status;
    }
}
