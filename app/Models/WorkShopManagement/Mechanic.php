<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    protected $table = 'wm_mechanics';
    protected $fillable = [
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


}
