<?php

namespace App\Models\Settings\vehicle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleModel extends Model
{
    use SoftDeletes;

    protected $table = 'CONFIG_VEHICLE_MODELS';

    protected $fillable = [
        'code',
        'model_name',
        'model_code',
        'brand_code',
        'body_type_code',
        'status',
        'created_by',
        'modified_by',
        'deleted_at'
    ];
}
