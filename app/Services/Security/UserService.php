<?php

namespace App\Services\Security;

use App\Helpers\StatusHelper;
use App\Models\reference\PHCMSEmployee;
use App\Models\Security\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

            dd($employee->phone);

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
        //(int)ParameterEncryption::decrypt();
        self::sync($id);
    }

    public static function sync($id): void
    {
        try {
            $user = User::find($id);

            $employee = PHCMSEmployee::where('con_per_no', $user->staff_no)
                ->where('con_st_code', '=', 'ACT')
                ->first();

            if (!empty($employee)) {
                User::where('staff_no', '=', $user->staff_no)
                    ->update(
                        [
                            'con_st_code' => StatusHelper::active(),
                            'email' => $employee->staff_email,
                            // 'username' => $request->login_name,
                            // 'phone' => $request->mobile_no,
                            // 'functional_section' => $request->user_unit,
                            'functional_section' => $employee->functional_section,
                            'bu_code' => $employee->bu_code,
                            'cc_code' => $employee->cc_code,
                            //'directorate' => $request->directorate,
                            //'user_unit' => $request->user_unit,
                            //'supervisor_code' => $request->staff_supervisorId,
                            //'supervisor_name' => $request->staff_supervisor,
                            'staff_no' => $employee->con_per_no,
                            'contract_type' => $employee->contract_type,
                            'con_wef_date' => $employee->con_wef_date,
                            'con_wet_date' => $employee->con_wet_date,
                            'name' => $employee->name,
                            'nrc' => $employee->nrc,
                            'sex' => $employee->sex,
                            'mobile_no' => $employee->mobile_no,
                            'group_type' => $employee->group_type,
                            'job_title' => $employee->job_title,
                            'grade' => $employee->grade,

                            //'directorate' => $employee_phcms->directorate,
                            'location' => $employee->location ?? $employee->functional_section,
                            'pay_point' => $employee->pay_point,
                            'job_code' => $employee->job_code ?? "--",
                            //'station' => $employee_phcms->station ?? "--",
                            'affiliated_union' => $employee->affiliated_union ?? "--",
                        ]
                    );
            } else {
                Log::info('User Not Found ! ');
            }
        } catch (QueryException $exception) {
            Log::info('Query For User Details Failed');
            Log::error($exception);
        } catch (Exception $e) {
            try {
                /*SystemErrorModel::create([
                    'class' => $e->getFile(),
                    'function' => $e->getLine(),
                    'msg' => str_split($e->getMessage(), 254),
                    'type' => $e->getCode(),
                    'user' => 'PHRIS_IPA_VIEW',
                ]);*/
            } catch (Exception $e) {
                Log::info('Error Occurred while Attempting to access PHRIS View');
            }
        }
    }

}
