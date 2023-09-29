<?php

namespace App\Services\Security;

use App\Constants\QueryComparisonOperator;
use App\Constants\TableColumns;
use App\Models\Security\User;
use App\Models\UserManagement\ProfileDelegation;
use Carbon\Carbon;

class ProfileDelegationService
{
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

}
