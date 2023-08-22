<?php

namespace App\Models\Settings\vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleBodyType extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'CONFIG_VEHICLE_BODY_TYPES';

    protected $fillable = [
        'guid',
        'name',
        'code',
        'status',
        'body_type_name',
        'date_created',
        'created_by'
    ];
}
