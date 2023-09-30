<?php

namespace App\Services\Security;

use App\Constants\ErrorMessages;
use App\Constants\QueryComparisonOperator;
use App\Constants\TableColumns;
use App\Exceptions\ActiveUserDelegationException;
use App\Http\Requests\DelegateProfile;
use App\Models\Security\User;
use App\Models\UserManagement\ProfileDelegation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProfileDelegationService
{
    private ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function getDelegatedProfileOwner(string $staffNumber): mixed
    {
        $activeDelegation = ProfileDelegation::where(
            'delegated_to', QueryComparisonOperator::EQUALS,
            $staffNumber
        )
            ->whereDate('period_from', '<', Carbon::now())
            ->whereDate('period_to', '>', Carbon::now())
            ->first();

        if (empty($activeDelegation)) {
            return null;
        }

        $user = User::where(TableColumns::STAFF_NUMBER,
            QueryComparisonOperator::EQUALS,
            $activeDelegation->profile_owner)->first();

        return $user->staff_no;
    }

    /**
     * @throws ActiveUserDelegationException
     */
    private function validate(DelegateProfile $request): void
    {
        // 1. user does not already have an active delegation
        $count = ProfileDelegation::where(
            'delegated_to',
            QueryComparisonOperator::EQUALS,
            $request->staffNumber)
            ->whereDate('period_from', '<', Carbon::now())
            ->whereDate('period_to', '>', Carbon::now())
            ->count();

        if ($count > 0) {
            throw new ActiveUserDelegationException(
                ErrorMessages::getMessage('err_0036')
            );
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
        ProfileDelegation::create(
            [
                'profile_owner' => $profileOwnerUserNo,
                'delegated_to' => $delegatedUserStaffNo,
                'owner_profile_id' => $profileOwnerProfile->id,
                'delegated_profile_id' => $delegatedUserProfile->id ?? 0,
                'period_from' => $request->get('startDate'),
                'period_to' => $request->get('endDate'),
                'justification' => $request->get('remarks'),
                'created_by' => $user->staff_no,
            ]
        );

        $roleIds = $delegatedUserProfile->pluck('id')->toArray();
        $this->profileService->assignProfile($delegatedUser->id, $roleIds);
        $this->profileService->revokeProfile($profileOwnerProfile->id, $roleIds);

        DB::commit();
    }

}
