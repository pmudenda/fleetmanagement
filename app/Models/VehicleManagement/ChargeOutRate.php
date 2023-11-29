<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargeOutRate extends Model
{
    protected $table = 'CONFIG_CHARGE_OUT_RATE';
    protected $fillable = [
        'vehicle_specification',
        'vehicle_description',
        'charge',
        'currency',
        'created_by'
    ];
}
