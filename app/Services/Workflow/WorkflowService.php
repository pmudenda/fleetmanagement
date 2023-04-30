<?php

namespace App\Services\Workflow;

use App\Helpers\Authorise;
use App\Helpers\Status;
use App\Models\Main\EformApprovalsModel;

class WorkflowService
{

    const FUNDS_ACKNOWLEDGMENT = 'fundsAcknowledgment';
    const FUNDS_DISBURSEMENT = 'fundsDisbursement';
    const EXPENDITURE_ACCOUNTANT = 'expenditureAccountant';
    const CHIEF_ACCOUNTANT = 'chiefAccountant';
    const DIRECTOR = 'director';
    const HUMAN_CAPITAL = 'humanCapital';
    const SENIOR_MANAGER = 'snr';
    const HEAD_OF_DEPARTMENT = 'hod';
    const SECURITY = 'security';
    const RECEIPT_APPROVAL = 'receiptApproval';
    const AUDITOR = 'auditor';
    const QUERY_RESOLUTION = 'queryResolution';
    const EXPENDITURE_ON_QUERY_RESOLUTION = 'expenditureOnQueryResolution';
    const POST_AUDIT = 'postAudit';
    const DESTINATION_APPROVAL = 'destinationApproval';
    const TRANSPORT = 'transport';

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
