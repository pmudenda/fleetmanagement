<?php

namespace App\Services\Security;

use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\DirectoratesModel;
use App\Models\Main\DivisionsModel;
use App\Models\Main\FunctionalUnitModel;
use App\Models\Main\GradesModel;
use App\Models\Main\LocationModel;
use App\Models\Main\PaypointModel;
use App\Models\Main\PositionModel;
use App\Models\Main\SystemErrorModel;
use App\Models\PhrisUserDetailsModel;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
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

        $employees = User::select('*' )
            ->join('ipa_phris_view', 'ipa_phris_view.con_per_no', '=', 'users.staff_no')
//            ->whereRaw('users.con_st_code != ipa_phris_view.con_st_code')
            ->get();


        foreach ($employees as $employee) {

            dd($employee->phone);

//            User::find($employee->id)->update([
//                'con_st_code' => $employee->employee_status
//            ]);

            //Remove profiles
            if($employee->employee_status == config('constants.phris_user_not_active')){
                // find the user
                $user = User::find( $employee->id );

            }

            log::info('Update Successful' . $employee->id);
        }

    }

    public static function updateEmployeeFullDetails($id)
    {
    }

    public function sync($id)
    {
        try {
            //get the user model
            $user = User::find($id);

            //get phcms details
            $phcmsUserDetails = PhrisUserDetailsModel::where('con_per_no', $user->staff_no)
                //->where('con_st_code', config('constants.phris_user_active'))
                ->first();

            if (!empty($phcmsUserDetails->name)) {
                //get the details
                $directorate = DirectoratesModel::where('name', $phcmsUserDetails->directorate)
                    ->get()
                    ->first();
                $position = PositionModel::where('name', $phcmsUserDetails->job_title)
                    ->get()
                    ->first();
                $grade = GradesModel::where('name', $phcmsUserDetails->grade)
                    ->get()
                    ->first();
                $location = LocationModel::where('name', $phcmsUserDetails->location)
                    ->get()
                    ->first();
                $payPoint = PaypointModel::where('name', $phcmsUserDetails->pay_point)
                    ->get()
                    ->first();
                $functionalSection = FunctionalUnitModel::where('name', $phcmsUserDetails->functional_section)
                    ->get()
                    ->first();
                $division = DivisionsModel::where('name', $phcmsUserDetails->pay_point)
                    ->get()
                    ->first();

                $userUnit = ConfigWorkFlow::where('user_unit_bc_code', $phcmsUserDetails->bu_code)
                    ->where('user_unit_cc_code', $phcmsUserDetails->cc_code)
                    ->get()
                    ->first();

                // update the model with the details from phris
                $user->name = $phcmsUserDetails->name ?? $user->name;
                $user->nrc = $phcmsUserDetails->nrc ?? $user->nrc;
                $user->contract_type = $phcmsUserDetails->contract_type ?? $user->contract_type;
                $user->con_st_code = $phcmsUserDetails->con_st_code ?? $user->con_st_code;
                $user->con_wef_date = $phcmsUserDetails->con_wef_date ?? $user->con_wef_date;
                $user->con_wet_date = $phcmsUserDetails->con_wet_date ?? $user->con_wet_date;
                $user->job_code = $phcmsUserDetails->job_code ?? $user->job_code;
                $user->grade_id = $grade->id ?? $user->grade_id;
                $user->positions_id = $position->id ?? $user->positions_id;
                $user->location_id = $location->id ?? $user->location_id;
                $user->user_division_id = $division->id ?? $user->user_division_id;
                $user->pay_point_id = $payPoint->id ?? $user->pay_point_id;
                $user->user_directorate_id = $directorate->id ?? $user->user_directorate_id;
                $user->functional_unit_id = $functionalSection->id ?? $user->functional_unit_id;
                $user->user_unit_id = $userUnit->id ?? $user->user_unit_id;
                $user->user_unit_code = $userUnit->user_unit_code ?? $user->user_unit_code;
                //save
                $user->save();

                //return detains
                Log::info('User Details Updated Successfully');

                return $user;
            } else {
                Log::info('User Details Failed to Updated! ');
            }
        } catch (QueryException $exception) {
            // You can check get the details of the error using `errorInfo`:
            Log::info('User Details Failed to Updated!. ERROR Message : ');
            Log::error($exception);
            Log::info('User Details Failed to Updated!');
        } catch (Exception $e) {
            try {
                SystemErrorModel::create([
                    'class' => $e->getFile(),
                    'function' => $e->getLine(),
                    'msg' => str_split($e->getMessage(), 254),
                    'type' => $e->getCode(),
                    'user' => 'PHRIS_IPA_VIEW',
                ]);
            } catch (Exception $e) {
                Log::info('PHRIS View Not Accessible while attempting to update user info');
            }
        }
    }

}
