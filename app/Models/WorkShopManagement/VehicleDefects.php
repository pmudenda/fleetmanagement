<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleDefects extends Model
{
    use HasFactory;

    protected $table = 'WM_VEHICLE_DEFECTS';
    protected $fillable = [
        'job_card_no',
        'veh_sys',
        'defect_category_code',
        'defect_code',
        'section_code',
        'created_by',
        'modified_by',
    ];
}
