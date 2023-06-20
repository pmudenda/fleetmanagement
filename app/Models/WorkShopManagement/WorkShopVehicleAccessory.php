<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShopVehicleAccessory extends Model
{
    use HasFactory;

    protected $table = 'WM_JOB_CARD_VEHICLE_ACCESSORIES';
    protected $fillable = [
        'workshop_reference',
        'job_card_no',
        'name',
        'code',
        'remarks',
        'is_present'
    ];
}
