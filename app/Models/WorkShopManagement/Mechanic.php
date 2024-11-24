<?php

namespace App\Models\WorkShopManagement;

use App\Models\Settings\WorkShop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class Mechanic extends Model {
    use Notifiable;

    protected $table = 'wm_mechanics';
    protected $fillable = [
        'email',
        'staff_no',
        'name',
        'workshop_code',
        'section_code',
        'status',
        'created_by',
        'modified_by',
        'is_supervisor',
        'updated_at',
        'extension',
        'area_code',
        'functional_section',
        'bu_code',
        'cc_code',
        'user_unit',
        'contract_type',
        'nrc',
        'mobile_no',
        'group_type',
        'job_title',
        'grade',
        'location',
        'pay_point',
        'job_code'
    ];

    public function workshops(): BelongsToMany
    {
        return $this->belongsToMany(WorkShop::class, 'MECHANIC_WORKSHOP')
            ->withTimestamps()
            ->using(MechanicWorkshop::class)
            ->withPivot([
                'is_supervisor',
                'deleted_at'
            ])
            ->wherePivotNull('deleted_at');
    }


}
