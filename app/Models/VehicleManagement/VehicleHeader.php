<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleHeader extends Model
{
    use SoftDeletes;

    protected $table = 'VM_VEHICLE_HEADER';
    protected $fillable = [
        'brand_name',
        'brand_code',
        'model_name',
        'model_code',
        'body_type_code',
        'body_type_name',
        'status',
        'on_boarding_status',
        'registration_type',
        'barcode',
        'has_tom_card',
        'invalid_odometer_entry',
        'mileage',
        'registration_number',
        'business_unit_code',
        'business_unit_name',
        'location_code',
        'location_name',
        'created_by',
        'created_name',
    ];
}
