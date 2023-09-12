<?php

namespace App\Services\WorkShopManagement;

use App\Constants\SystemMessages;
use App\Enums\ConfigurationTypes;
use App\Enums\Modules;
use App\Enums\RequisitionItemTypes;
use App\Enums\WorkflowProcessCodes;
use App\Events\WorkOrderCompleted;
use App\Exceptions\DuplicateDefectException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\WorkflowTaskCreationFailedException;
use App\Helpers\StatusHelper;
use App\Http\Requests\UserProfileUpdate;
use App\Http\Requests\VehicleDefectsRequest;
use App\Http\Requests\WorkShopManagement\JobCardRequest;
use App\Http\Requests\WorkShopManagement\JobCardTaskAssignment;
use App\Http\Requests\WorkShopManagement\JobCardTaskReassignment;
use App\Http\Requests\WorkShopManagement\WorkOrderClosure;
use App\Models\Common\File;
use App\Models\MaterialHeader;
use App\Models\Reference\PHCMSEmployee;
use App\Models\Security\User;
use App\Models\Settings\Accessory;
use App\Models\Settings\GeneralTable;
use App\Models\VehicleManagement\VehicleHeader;
use App\Models\WorkShopManagement\AssessmentObservation;
use App\Models\WorkShopManagement\JobCardHeader;
use App\Models\WorkShopManagement\Mechanic;
use App\Models\WorkShopManagement\WorkShopComment;
use App\Models\WorkShopManagement\WorkshopLabour;
use App\Models\WorkShopManagement\WorkShopTable;
use App\Models\WorkShopManagement\WorkShopVehicleAccessory;
use App\Models\WorkShopManagement\WorkShopVehicleDefect;
use App\Services\FileUploads\FileUploadService;
use App\Services\Integration\ProcurementSystemIntegrationService;
use App\Services\Logging\HistoryService;
use App\Services\Workflow\DocumentNumberGenerationService;
use App\Services\Workflow\WorkflowService;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class MechanicsService
{
    public function updateDetails(UserProfileUpdate $request): void
    {
        DB::beginTransaction();

        $id = $request->input('mechanicId');

        Mechanic::where('id', '=', $id)
            ->update(
                [
                    'area_code' => $request->get('area'),
                    'workshop_code' => $request->get('workshop_code'),
                    'work_shop_section' => $request->get('is_supervisor'),
                    'is_supervisor' => $request->get('staff_supervisor'),
                ]
            );

        DB::commit();

    }

    public function syncMechanicFullDetails(mixed $userId): void
    {
        $id = $userId;
        Log::info('Start Syncing Data ' . $userId);
        self::sync($id);
    }

    private function sync($id): void
    {
        try {
            $user = Mechanic::find($id);

            $employee = PHCMSEmployee::where('con_per_no', $user->staff_no)
                ->where('con_st_code', '=', 'ACT')
                ->first();

            Log::info('Syncing User Data For ' . $employee->con_per_no);

            if (empty($employee)) {
                throw new UserNotFoundException("User Not Found");
            }

            DB::beginTransaction();
            Mechanic::where('staff_no', '=', $user->staff_no)
                ->update(
                    /*[
                        'con_st_code' => StatusHelper::active(),
                        'email' => strtoupper($employee->staff_email),
                        'functional_section' => $employee->functional_section,
                        'directorate' => $employee->directorate,
                        'user_unit' => $employee->functional_section,
                        'bu_code' => $employee->bu_code,
                        'cc_code' => $employee->cc_code,
                        'staff_no' => $employee->con_per_no,
                        'name' => $employee->name,
                        'nrc' => $employee->nrc,
                        'mobile_no' => $employee->mobile_no,
                        'group_type' => $employee->group_type,
                        'job_title' => $employee->job_title,
                        'grade' => $employee->grade,
                        'location' => $employee->location ?? $employee->functional_section,
                        'pay_point' => $employee->pay_point,
                        'job_code' => $employee->job_code ?? "--",
                    ]*/
                );
            DB::commit();


        } catch (QueryException $exception) {
            Log::info('Query For User Details Failed');
            Log::error($exception);
        } catch (Exception $e) {
            Log::info('Error Occurred while Attempting to access PHRIS View');
            Log::error($e);
        }
    }
}
