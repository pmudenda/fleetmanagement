<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Model;

class VehicleSystemDefects extends Model
{
    protected $table = 'CONFIG_VEHICLE_DEFECTS';

    protected $fillable = [
        'type_code',
        'parent',
        'code',
        'status',
        'description',
    ];
}
