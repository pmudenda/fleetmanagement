<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory;
    //use SoftDeletes;

    protected $table = 'VM_ASSIGNMENTS';
    protected $fillable = [

        'cost_center',
        'cost_center_name',
        'directorate',
        'directorate_name',

        'isPoolVehicle',
        'mileageExempt',

        'isTeamAssigned',
        //'operatorName',
        //'operatorStaffNumber',
        //'superVisorName',
        //'superVisorStaffNumber',
        //'casualStaffNumber',
        //'casualStaffName',
        //'businessArea',
        //'costCenter',

        'vehicle_header_id',
        'created_by',
        'created_name',
        'business_unit',

        'business_unit_name',
        'responsible_head_id',
        'responsible_head_name',

        'business_area_code',
        'business_area_name',

        'assignment_state'
    ];
}
