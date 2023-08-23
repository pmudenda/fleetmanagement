<?php

namespace App\Models\Security;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\MaterialHeader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    //use HasPermissionsTrait;
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
        'location_id',
        'pay_point_id',
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
        'user_directorate_id',
        'station',
        'last_login',
        'total_logins',
        'area_code',
        'last_session_id',
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

    public static function swapping($user): void
    {
        Log::info('Checking Other Session For User ' . $user->staff_no);
        try {
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
