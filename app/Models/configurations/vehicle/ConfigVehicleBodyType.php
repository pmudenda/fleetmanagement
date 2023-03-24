<?php

namespace App\Models\configurations\vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigVehicleBodyType extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'CONFIG_VEHICLE_BODY_TYPES';

    protected $fillable = [
        'guid',
        'name',
        'status',
        'body_type_name',
        'date_created'
    ];
}
