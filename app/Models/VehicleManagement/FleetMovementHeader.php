<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Model;

class FleetMovementHeader extends Model
{
    protected $table = 'vm_fleet_movement_header';

    protected $fillable = [
        'period_from',
        'period_to',
        'odometer_start',
        'odometer_end',
        'odometer_diff',
        'business_area',
        'cost_center',
        'reg_no',
        'logged_by',
        'serial_no',
        'batch_no',
        'hours_start',
        'hours_end',
        'hours_done',
        'authorised_by',
        'auth_date',
        'driver',
        'driver_name',
        'source',
        'auto_void_reason',
        'int_order',
        'accounted',
        'acc_date',
        'machine_type',
    ];
}
