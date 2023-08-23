<?php

namespace App\Models\Settings\vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleBodyType extends Model
{
    use SoftDeletes;

    protected $table = 'CONFIG_VEHICLE_BODY_TYPES';

    protected $fillable = [
        'name',
        'code',
        'status',
        'created_by',
        'modified_by',
        'deleted_at'
    ];
}
