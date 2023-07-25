<?php

namespace App\Services\Security;

use App\Constants\ErrorMessages;
use App\Exceptions\UserNotActiveException;
use App\Exceptions\UserNotFoundException;
use App\Helpers\StatusHelper;
use App\Http\Requests\UserProfileUpdate;
use App\Models\reference\PHCMSEmployee;
use App\Models\Security\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * get currently logged in user
     *
     **/
    public static function getLoggedInUser()
    {
        return Auth::user();
    }

    public static function synchronizeData()
    {
//        $employees = User::select('ipa_phris_view.con_per_no', 'ipa_phris_view.con_st_code', 'users.id')
//            ->join('ipa_phris_view', 'ipa_phris_view.con_per_no', '=', 'users.staff_no')
//            ->whereRaw('users.con_st_code != ipa_phris_view.con_st_code')
//            ->get();

        $employees = User::select('*')
            ->join('ipa_phris_view', 'ipa_phris_view.con_per_no', '=', 'users.staff_no')
//            ->whereRaw('users.con_st_code != ipa_phris_view.con_st_code')
            ->get();


        foreach ($employees as $employee) {
//            User::find($employee->id)->update([
//                'con_st_code' => $employee->employee_status
//            ]);

            //Remove profiles
            if ($employee->employee_status == config('constants.phris_user_not_active')) {
                // find the user
                $user = User::find($employee->id);

            }

            log::info('Update Successful' . $employee->id);
        }

    }

    public static function syncEmployeeFullDetails($userId): void
    {
        $id = $userId;
        //(int) ParameterEncryption::decrypt();
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
                        'email' => $employee->staff_email,
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

    public static function updateUserDetails(UserProfileUpdate $request): void
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
            $user = User::where('id','=', $id)->first();
            $user->roles()->syncWithoutDetaching((int)$request->get('user_profile'));
        }

        DB::commit();

    }

}
