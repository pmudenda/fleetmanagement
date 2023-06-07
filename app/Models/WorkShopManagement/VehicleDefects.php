<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleDefects extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'WM_VEHICLE_DEFECTS';

    protected $fillable = [
        'job_card_no',
        'veh_sys',
        'defect_category_code',
        'defect_code',
        'section_code',
        'date_def',
        'created_by',
        'modified_by',
        'deleted_at'
    ];
}
