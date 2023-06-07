<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleDefects extends Model
{
    use HasFactory;

    protected $table = 'WM_VEHICLE_DEFECTS';
    protected $fillable = [];
}
