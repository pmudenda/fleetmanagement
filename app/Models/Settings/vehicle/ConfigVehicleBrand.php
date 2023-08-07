<?php

namespace App\Models\Settings\vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigVehicleBrand extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'CONFIG_VEHICLE_BRANDS';
    protected $fillable = [
        'guid',
        'name',
        'code',
        'status',
        'date_created',
        'created_by'
    ];
}
