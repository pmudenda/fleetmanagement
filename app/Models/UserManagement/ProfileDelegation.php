<?php

namespace App\Models\UserManagement;

use App\Models\Security\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class  ProfileDelegation extends Model
{
    protected $table = 'sec_profile_delegation';
    protected $fillable = [
        'profile_owner',
        'delegated_to',
        'owner_profile_id',
        'delegated_profile_id',
        'period_from',
        'period_to',
        'justification',
        'cancellation_remarks',
        'created_by',
        'modified_by',
        'cancelled_by',
        'date_cancelled',
    ];

    public function delegatedRole(): HasOne
    {
        return $this->hasOne(Role::class, 'owner_profile_id', 'id');
    }
}
