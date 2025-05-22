<?php

namespace App\Models\Security;

use App\Models\Common\MaterialHeader;
use App\Models\UserManagement\ProfileDelegation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasPermissions;
    use HasRoles;

    protected $table = 'SEC_USERS';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'staff_no',
        'avatar',
        'phone',
        'extension',
        'nrc',
        'contract_type',
        'con_st_code',
        'two_fac_auth_status',
        'password_changed',
        'change_password_next_login',
        'functional_unit_id',
        'unit_column',
        'code_column',
        'profile_code',
        'profile_name',
        'profile_id_delegated',
        'grade_id',
        'job_code',
        'user_unit_code',
        'user_unit_id',
        'positions_id',
        'work_shop_code',
        'user_division_id',
        'station',
        'last_login',
        'total_logins',
        'area_code',
        'job_title',
        'supervisor_code',
        'supervisor_name',
        'user_unit',
        'directorate',
        'cc_code',
        'bu_code',
        'grade',
        'functional_section',
        'username',
        'mobile_no'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function requisitions(): HasMany
    {
        return $this->hasMany(MaterialHeader::class, 'requested_by', 'staff_no');
    }

    public function delegatedProfile(): HasOne
    {
        return $this->hasOne(ProfileDelegation::class, 'delegated_to', 'id')
            ->whereDate('period_from', '<=', Carbon::now())
            ->whereDate('period_to', '>', Carbon::now())
            ->whereNull('date_cancelled');
    }

    public function profileDelegation(): HasOne
    {
        return $this->hasOne(ProfileDelegation::class, 'profile_owner')
            ->whereDate('period_from', '<=', Carbon::now())
            ->whereDate('period_to', '>', Carbon::now())
            ->whereNull('date_cancelled');
    }

    public function approvers(): HasMany {
        return $this->hasMany(EmployeeApprovers::class , 'bu_scode', 'business_unit_code')->where('cc_code','cost_center_code');
    }


}
