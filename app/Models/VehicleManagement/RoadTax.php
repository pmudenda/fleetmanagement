<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoadTax extends Model
{
    use SoftDeletes;
    protected $table = 'vm_road_tax';
}
