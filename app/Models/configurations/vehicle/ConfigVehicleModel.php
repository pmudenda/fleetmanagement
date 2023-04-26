<?php

namespace App\Models\configurations\vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigVehicleModel extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'CONFIG_VEHICLE_MODELS';

    protected $fillable = [
        'brand_guid',
        'brand_name',
        'model_guid',
        'model_name',
        'model_code',
        'status',
        'code',
        'date_created',
        'created_by'
    ];
}
