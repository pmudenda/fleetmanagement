<?php

namespace App\Models\configurations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleAccessories extends Model
{
    use HasFactory;
    protected $table = 'VM_VEHICLE_ACCESSORIES';

    protected $fillable = [
        'vehicle_header_id',
        'name',
        'code',
        'remarks',
        'response'
    ];
}
