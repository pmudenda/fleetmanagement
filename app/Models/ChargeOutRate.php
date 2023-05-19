<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargeOutRate extends Model
{
    use HasFactory;

    protected $table = 'CONFIG_CHARGE_OUT_RATE';
    protected $fillable = [
        'vehicle_specification',
        'vehicle_description',
        'charge',
        'created_by'
    ];
}
