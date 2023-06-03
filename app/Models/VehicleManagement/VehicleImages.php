<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleImages extends Model
{
    use HasFactory;

    protected $table = 'VM_VEHICLE_IMAGES';

    protected $fillable = [
        'vehicle_header_id',
        'file_name',
        'file_path',
        'view',
        'created_by',
        'created_name',
        'period_start',
        'period_end'
    ];
}
