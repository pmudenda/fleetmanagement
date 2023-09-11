<?php

namespace App\Services\Security;

use App\Constants\ErrorMessages;
use App\Constants\SystemMessages;
use App\Exceptions\UserNotActiveException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserOnBoardingException;
use App\Helpers\StatusHelper;
use App\Http\Requests\UserOnboardingRequest;
use App\Http\Requests\UserProfileUpdate;
use App\Models\Reference\PHCMSEmployee;
use App\Models\Security\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserService
{
    public static function synchronizeData()
    {
        $employees = User::select('*')
            ->join('ipa_phris_view', 'ipa_phris_view.con_per_no', '=', 'users.staff_no')
            ->get();


        foreach ($employees as $employee) {
            //Remove profiles
            if ($employee->employee_status == config('constants.phris_user_not_active')) {
                // find the user
                User::find($employee->id);
            }

            log::info('Update Successful' . $employee->id);
        }

    }

    public static function syncEmployeeFullDetails($userId): void
    {
        $id = $userId;
        Log::info('Start Syncing Data ' . $userId);
        self::sync($id);
    }

    public static function sync($id): void
    {
        try {
            $user = User::find($id);

            $employee = PHCMSEmployee::where('con_per_no', $user->staff_no)
                ->where('con_st_code', '=', 'ACT')
                ->first();

            Log::info('Syncing User Data For ' . $employee->con_per_no);

            if (empty($employee)) {
                throw new UserNotFoundException("User Not Found");
            }

            DB::beginTransaction();
            User::where('staff_no', '=', $user->staff_no)
                ->update(
                    [
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
     * @throws UserNotActiveException
     */
    public static function searchEmployee(string $searchParam)
    {
        if (str_starts_with($searchParam, 'C7') || str_starts_with($searchParam, '7')) {
            $dataset = PHCMSEmployee::select('*')
                ->where('con_per_no', $searchParam)
                ->where('con_st_code', '=', 'ACT')
                ->whereNull('alt_per_no')
                ->first();
        } else {
            $dataset = PHCMSEmployee::select('*')
                ->where('name', 'LIKE', "%{$searchParam}%")
                ->where('con_st_code', '=', 'ACT')
                ->where(function ($query) {
                    $query->where('con_per_no', 'LIKE', "C7%")
                        ->orWhere('con_per_no', 'LIKE', "7%");
                })
                ->get();

        }

        if (empty($dataset)) {
            throw new UserNotActiveException(ErrorMessages::getMessage('err_0019'));
        }

        return $dataset;
    }

    public function updateUserDetails(UserProfileUpdate $request): void
    {
        DB::beginTransaction();

        $id = $request->input('userId');

        User::where('id', '=', $id)
            ->update(
                [
                    'area_code' => $request->get('area'),
                    'supervisor_code' => $request->get('staff_supervisorId'),
                    'supervisor_name' => $request->get('staff_supervisor'),
                    'name' => $request->get('name')
                ]
            );

        if ($request->has('user_profile') || !empty($request->get('user_profile'))) {
            $user = User::where('id', '=', $id)->first();
            /// $user->roles()->syncWithoutDetaching((int)$request->get('user_profile'));
            $user->roles()->sync((int)$request->get('user_profile'));
        }

        DB::commit();

    }

    /**
     * @param UserOnboardingRequest $request
     * @return mixed
     * @throws UserOnBoardingException
     */
    public function createUser(UserOnboardingRequest $request): mixed
    {
        $validateWithHCMS = config('systeminfo.enableUserValidation');

        // move logic to database function
        if ($validateWithHCMS) {
            try {
                $employee_phcms = PHCMSEmployee::where('con_per_no', $request->staff_number)
                    ->where('con_st_code', '=', 'ACT')
                    ->first();
                if (empty($employee_phcms)) {
                    throw new UserNotFoundException("User Not Found");
                }
            } catch (\Exception $ex) {
                Log::error($ex);
                throw new UserOnBoardingException(
                    str_replace('@user_name',
                        $request->staff_number,
                        SystemMessages::USER_NOT_CREATED
                    )
                );
            }
            DB::beginTransaction();
            $user = User::firstOrCreate(
                [
                    'staff_no' => $request->staff_number,
                ],
                [
                    'con_st_code' => StatusHelper::active(),
                    'password' => Hash::make($request->password),
                    'email' => strtoupper($request->staff_email),
                    'username' => $request->login_name,
                    'phone' => $request->mobile_no,
                    'guid' => Str::uuid(),
                    'area_code' => $request->get('business_area'),
                    'functional_section' => $request->user_unit,
                    'bu_code' => $request->business_unit_code,
                    'cc_code' => $request->cost_center_code,
                    'directorate' => $request->directorate,
                    'user_unit' => $request->user_unit,
                    'supervisor_code' => $request->staff_supervisorId,
                    'supervisor_name' => $request->staff_supervisor,

                    'staff_no' => $employee_phcms->con_per_no,
                    'contract_type' => $employee_phcms->contract_type,
                    'name' => $employee_phcms->name,
                    'nrc' => $employee_phcms->nrc,
                    'mobile_no' => $employee_phcms->mobile_no,
                    'group_type' => $employee_phcms->group_type,
                    'job_title' => $employee_phcms->job_title,
                    'grade' => $employee_phcms->grade,
                    'location' => $employee_phcms->location ?? $employee_phcms->functional_section,
                    'pay_point' => $employee_phcms->pay_point,
                    'job_code' => $employee_phcms->job_code ?? "--",
                ]
            );
            DB::commit();
        } else {

            DB::beginTransaction();
            $user = User::firstOrCreate(
                [
                    'staff_no' => $request->staff_number,
                ],
                [
                    'con_st_code' => StatusHelper::active(),
                    'password' => Hash::make($request->password),
                    'name' => $request->name,
                    'staff_no' => $request->staff_number,
                    'email' => $request->staff_email,
                    'username' => $request->login_name,
                    'phone' => $request->mobile_no,
                    'mobile_no' => $request->mobile_no,
                    'functional_section' => $request->user_unit,
                    'grade' => $request->grade,
                    'bu_code' => $request->business_unit_code,
                    'cc_code' => $request->cost_center_code,
                    'directorate' => $request->directorate,
                    'user_unit' => $request->user_unit,
                    'supervisor_code' => $request->staff_supervisorId,
                    'supervisor_name' => $request->staff_supervisor,
                    'job_title' => $request->job_title,
                    'guid' => Str::uuid(),
                    'area_code' => $request->get('business_area'),
                ],
            );
            DB::commit();
        }

        if ($request->has('user_profile') || !empty($request->get('user_profile'))) {
            $user->roles()->syncWithoutDetaching((int)$request->get('user_profile'));
        }

        return true;
    }

}
