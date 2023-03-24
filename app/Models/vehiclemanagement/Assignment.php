<?php

namespace App\Models\vehiclemanagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'VM_ASSIGNMENTS';
    protected $fillable = [
        'businessArea',
        'casualStaffNumber',
        'casualStaffName',
        'costCenter',
        'directorate',
        'isPoolVehicle',
        'isTeamAssigned',
        'mileageExempt',
        'operatorName',
        'operatorStaffNumber',
        'superVisorName',
        'superVisorStaffNumber',
        'vehicle_header_id',
        'created_by',
        'created_name'
    ];
}
