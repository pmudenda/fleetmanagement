<?php

namespace App\Services\WorkShopManagement;

use App\Constants\SystemMessages;
use App\Exceptions\UserNotFoundException;
use App\Helpers\StatusHelper;
use App\Http\Requests\MechanicOnboarding;
use App\Http\Requests\UserProfileUpdate;
use App\Models\Reference\PHCMSEmployee;
use App\Models\WorkShopManagement\Mechanic;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

            Log::info('Syncing User Data For ' . $user->staff_no);

            if (empty($employee)) {
                throw new UserNotFoundException("User Not Found");
            }

            DB::beginTransaction();
            Mechanic::where('staff_no', '=', $user->staff_no)
                ->update(
                    [
                        'status' => StatusHelper::active(),
                        'staff_email' => strtoupper($employee->staff_email),
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
                    ]
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

    /**
     * @throws UserNotFoundException
     */
    public function createMechanic(MechanicOnboarding $request): bool
    {
        DB::beginTransaction();

        $employee_phcms = PHCMSEmployee::where(
            'con_per_no',
            '=',
            $request->staff_number
        )->where('con_st_code',
            '=',
            'ACT')->first();

        if (empty($employee_phcms)) {
            throw new UserNotFoundException(str_replace('@user_name',
                $request->staff_number,
                SystemMessages::USER_NOT_VERIFIED
            ));
        }

        $user = auth()->user();

        $data = [
            'staff_email' => strtoupper($request->staff_email),
            'extension' => $request->mobile_no,
            'area_code' => $request->get('business_area'),
            'functional_section' => $request->user_unit,
            'bu_code' => $request->business_unit_code,
            'cc_code' => $request->cost_center_code,
            'directorate' => $request->directorate,
            'user_unit' => $request->user_unit,

            'staff_no' => $employee_phcms->con_per_no,
            'name' => $employee_phcms->name,
            'workshop_code' => $request->workshopCode,
            'section_code' => $request->workShopSection,
            'status' => StatusHelper::active(),
            'created_by' => $user->staff_no,

            'contract_type' => $employee_phcms->contract_type,
            'nrc' => $employee_phcms->nrc,
            'mobile_no' => $employee_phcms->mobile_no,
            'group_type' => $employee_phcms->group_type,
            'job_title' => $employee_phcms->job_title,
            'grade' => $employee_phcms->grade,

            'location' => $employee_phcms->location ?? $employee_phcms->functional_section,
            'pay_point' => $employee_phcms->pay_point,
            'job_code' => $employee_phcms->job_code ?? "--",

            /*
             * supervisor_code' => $request->staff_supervisorId
             * supervisor_name' => $request->staff_supervisor,
            */
        ];

        Mechanic::firstOrCreate(
            [
                'staff_no' => $request->staff_number,
            ],
            $data
        );

        DB::commit();

        return true;

    }
}
