<?php

namespace App\Policies;

use App\Enums\GatePassStatus;
use App\Models\GatePass\GatePass;
use App\Models\Security\User;

class GatePassPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canAny(['gatepass_create','gatepass_check']) || $this->viewAssigned($user) || $this->viewAll($user);
    }

    public function viewAll(User $user): bool
    {
        return $user->canAny(['gatepass_view_all']);
    }


    public function viewAssigned(User $user): bool
    {
        return $user->canAny(['gatepass_authorize_local','gatepass_authorise_out_of_town']);
    }

    public function viewUnchecked(User $user): bool
    {
        return $user->canAny(['gatepass_check']);
    }

    public function check(User $user, GatePass $gatePass): bool
    {
        return $this->viewUnchecked($user) && $gatePass->status == GatePassStatus::AUTHORIZED;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('gatepass_create');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GatePass $gatePass): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GatePass $gatePass): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GatePass $gatePass): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GatePass $gatePass): bool
    {
        //
    }

    public function authorise(User $user, GatePass $gatePass): bool
    {
        return $user->canAny(['gatepass_authorize_local','gatepass_authorise_out_of_town']) &&
            $gatePass->status == GatePassStatus::NEW;
    }
}
