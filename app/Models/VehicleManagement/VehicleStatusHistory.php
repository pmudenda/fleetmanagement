<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Model;

class VehicleStatusHistory extends Model
{
    protected $table = 'gen_vehicle_status_history';
    protected $fillable = [
        'created_by',
        'updated_by',
        'code',
        'reference',
        'page',
        'description',
        'reg_no',
        'status'
    ];
}
