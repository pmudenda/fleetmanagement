<?php

namespace App\Models\configurations\vehicle;

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
        'status',
        'date_created'
    ];
}
