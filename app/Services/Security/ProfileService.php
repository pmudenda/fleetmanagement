<?php

namespace App\Services\Security;

use App\Models\Security\User;

class ProfileService
{

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
