<?php

namespace App\Services\Security;

use App\Constants\ErrorMessages;
use App\Constants\QueryComparisonOperator;
use App\Constants\TableColumns;
use App\Exceptions\ActiveUserDelegationException;
use App\Exceptions\UserDataSyncException;
use App\Exceptions\UserNotActiveException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserOnBoardingException;
use App\Helpers\StatusHelper;
use App\Http\Requests\DelegateProfile;
use App\Http\Requests\UserOnboardingRequest;
use App\Http\Requests\UserProfileUpdate;
use App\Models\Reference\PHCMSEmployee;
use App\Models\Security\User;
use App\Models\UserManagement\ProfileDelegation;
use App\Services\Logging\HistoryService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PDO;

class UserService
{
    const RESULT = ":result";

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

            Log::info('Syncing User Data For ' . $user->staff_no);

            if (empty($user)) {
                throw new UserNotFoundException("User Not Found");
            }

            $pdo = DB::getPdo();
            $modifiedBy = auth()->user()->staff_no;
            $stmt = $pdo->prepare(
                "begin :result := pkg_employee.fn_sync_user(:p_staff_no, :p_modified_by); end;"
            );

            $userToSync = $user->staff_no;
            $stmt->bindParam(self::RESULT, $results, PDO::PARAM_STR, 2000);
            $stmt->bindParam(":p_staff_no", $userToSync);
            $stmt->bindParam(":p_modified_by", $modifiedBy);
            $stmt->execute();

            Log::info($results);

            if (str_starts_with($results, "0")) {
                throw new UserDataSyncException($results);
            }

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
    public function searchEmployee(string $searchParam)
    {
        if (str_starts_with($searchParam, 'C7') || str_starts_with($searchParam, '7')) {

            $dataset = PHCMSEmployee::select('*')
                ->where(
                    TableColumns::PHCMS_STAFF_NUMBER,
                    QueryComparisonOperator::EQUALS,
                    $searchParam
                )
                ->where(TableColumns::PHCMS_STATUS,
                    QueryComparisonOperator::EQUALS,
                    'ACT')
                ->whereNull('alt_per_no')
                ->first();
        } else {
            $dataset = PHCMSEmployee::select('*')
                ->where('name', 'LIKE', "%{$searchParam}%")
                ->where(TableColumns::PHCMS_STATUS,
                    QueryComparisonOperator::EQUALS,
                    'ACT')
                ->whereNull('alt_per_no')
                ->where(function ($query) {
                    $query->where(TableColumns::PHCMS_STAFF_NUMBER, 'LIKE', "C7%")
                        ->orWhere(TableColumns::PHCMS_STAFF_NUMBER, 'LIKE', "7%");
                })
                ->get();
        }

        if (empty($dataset)) {
            throw new UserNotActiveException(
                ErrorMessages::getMessage(
                    'err_0019'
                )
            );
        }

        return $dataset;
    }

    /**
     * @throws UserNotActiveException
     */
    public static function searchUser(string $searchParam)
    {
        $dataset = User::select('*')
            ->where('name', 'LIKE', "%{$searchParam}%")
            ->where(TableColumns::PHCMS_STAFF_NUMBER,
                QueryComparisonOperator::EQUALS,
                StatusHelper::active())
            ->where(function ($query) {
                $query->where('con_per_no', 'LIKE', "C7%")
                    ->orWhere('con_per_no', 'LIKE', "7%");
            })
            ->get();

        if (empty($dataset)) {
            throw new UserNotActiveException(ErrorMessages::getMessage('err_0019'));
        }

        return $dataset;
    }

    /**
     * Clears all other user sessions
     * @param $user
     * @return void
     */
    public static function logoutOtherDevices($user): void
    {
        Log::info(
            "Single Session Enabled "
            . (int)config('systeminfo.enableSingleSessionManagement')
        );

        if (config('systeminfo.enableSingleSessionManagement')) {
            Log::info('Checking Other Session For User ' . $user->staff_no);
            try {
                Log::info("Logging Out All User Devices");
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->update([
                        'id' => DB::raw("concat('OUTMAN_', concat(user_id, concat('_', id)))"),
                        'user_id' => null,
                    ]);
            } catch (\Exception $e) {
                Log::error($e);
            }
        }
    }

    /**
     * @throws ActiveUserDelegationException
     */
    public function initiateDelegation(DelegateProfile $request): void
    {
        $this->validate($request);
        $user = auth()->user();

        $profileOwnerUserNo = $request->get('profileOwner');
        $delegatedUserStaffNo = $request->get('staffNumber');

        $profileOwner = User::where(
            TableColumns::STAFF_NUMBER,
            QueryComparisonOperator::EQUALS,
            $profileOwnerUserNo
        )->first();
        $profileOwnerProfile = $profileOwner->roles()->first();

        $delegatedUser = User::where(
            TableColumns::STAFF_NUMBER,
            QueryComparisonOperator::EQUALS,
            $delegatedUserStaffNo
        )->first();
        $delegatedUserProfile = $delegatedUser->roles()->first();

        DB::beginTransaction();
        ProfileDelegation::create([
            'profile_owner' => $profileOwnerUserNo,
            'delegated_to' => $delegatedUserStaffNo,
            'owner_profile_id' => $profileOwnerProfile->id,
            'delegated_profile_id' => $delegatedUserProfile->id ?? 0,
            'period_from' => $request->get('startDate'),
            'period_to' => $request->get('endDate'),
            'justification' => $request->get('remarks'),
            'created_by' => $user->staff_no,
        ]);

        $this->assignProfile($delegatedUser->id, [$profileOwnerProfile->id]);

        DB::commit();
    }

    public function updateUserDetails(UserProfileUpdate $request): void
    {
        DB::beginTransaction();

        $id = $request->input('userId');

        User::where('id',
            QueryComparisonOperator::EQUALS,
            $id)
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
            $user->roles()->sync((int)$request->get('user_profile'));
        }

        DB::commit();

    }

    /**
     * @param UserOnboardingRequest $request
     * @return bool
     * @throws UserOnBoardingException
     * @throws UserNotFoundException
     */
    public function createUser(UserOnboardingRequest $request): bool
    {
        $validateWithHCMS = config('systeminfo.enableUserValidation');

        // move logic to database function

        if ($validateWithHCMS) {
            try {
                $employee = PHCMSEmployee::where(
                    TableColumns::PHCMS_STAFF_NUMBER,
                    QueryComparisonOperator::EQUALS,
                    $request->staff_number
                )->where(TableColumns::PHCMS_STATUS,
                    QueryComparisonOperator::EQUALS,
                    'ACT')->first();
            } catch (\Exception $ex) {
                Log::error($ex);
            }

            $password = Hash::make($request->password);
            $email = strtoupper($request->staff_email);
            $pdo = DB::getPdo();
            $modifiedBy = auth()->user()->staff_no;
            $stmt = $pdo->prepare(
                "begin :result := pkg_employee.fn_create_user(
                :p_staff_no, :p_modified_by); end;"
            );
            $stmt->bindParam(self::RESULT, $results, PDO::PARAM_STR, 2000);
            $stmt->bindParam(":p_created_by", $modifiedBy);
            $stmt->bindParam(":p_staff_no", $request->staff_number);
            $stmt->bindParam(":p_password", $password);
            $stmt->bindParam(":p_email", $email);
            $stmt->bindParam(":p_username", $request->login_name);
            $stmt->bindParam(":p_phone", $request->mobile_no);
            $stmt->bindParam(":p_area_code", $request->business_area);
            $stmt->bindParam(":p_functional_section", $request->user_unit);
            $stmt->bindParam(":p_bu_code", $request->business_unit_code);
            $stmt->bindParam(":p_cc_code", $request->cost_center_code);
            $stmt->bindParam(":p_directorate", $request->directorate);
            $stmt->bindParam(":p_user_unit", $request->user_unit);
            $stmt->bindParam(":p_supervisor_code", $request->staff_supervisorId);
            $stmt->bindParam(":p_supervisor_name", $request->staff_supervisor);
            //$stmt->bindParam(":p_avatar", );
            //$stmt->bindParam(":p_work_shop_code", '');
            $stmt->execute();

            if (str_starts_with('0', $results) && str_contains($results, 'Not Found')) {
                throw new UserNotFoundException($results);
            }

            if (str_starts_with('0', $results) && str_contains($results, 'Occurred')) {
                throw new UserOnBoardingException(
                    str_replace('@user_name',
                        $request->staff_number,
                        $results
                    )
                );
            }
        }

        $user = User::where('staff_no', QueryComparisonOperator::EQUALS, $request->staff_number);

        if ($request->has('user_profile') || !empty($request->get('user_profile'))) {
            DB::beginTransaction();
            $user->roles()->sync((int)$request->get('user_profile'));
            DB::commit();

            HistoryService::record(
                $user->toArray(),
                "N/A",
                'Create User',
                'User Onboarding with profile'
            );

        } else {
            HistoryService::record(
                $user->toArray(),
                "N/A",
                'Create User',
                'User Onboarding with no profile'
            );
        }

        return true;
    }

    /**
     * @throws ActiveUserDelegationException
     */
    private function validate(DelegateProfile $request): void
    {
        // 1. user does not already have an active delegation
        $count = ProfileDelegation::where('delegated_to', '=', $request->staffNumber)
            ->whereDate('period_from', '<', Carbon::now())
            ->whereDate('period_to', '>', Carbon::now())
            ->count();

        if ($count > 0) {
            throw new ActiveUserDelegationException(
                ErrorMessages::getMessage('err_0036')
            );
        }
    }

    public function assignProfile($userId, array $roleIds): void
    {
        $user = User::find($userId);
        $user->roles()->sync($roleIds);
    }

    public function revokeProfile(mixed $userId, mixed $roleIds): void
    {
        $user = User::find($userId);
        $user->roles()->detach($roleIds);
    }
}
