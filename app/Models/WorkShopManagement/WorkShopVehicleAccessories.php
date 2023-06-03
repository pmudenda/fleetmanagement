<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShopVehicleAccessories extends Model
{
    use HasFactory;

    protected $table = 'WM_JOB_CARD_VEHICLE_ACCESSORIES';
    protected $fillable = [
        'job_card_no',
        'name',
        'code',
        'remarks',
        'response'
    ];
}
