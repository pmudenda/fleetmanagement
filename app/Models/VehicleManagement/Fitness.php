<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fitness extends Model
{
    use SoftDeletes;
    protected $table = 'vm_fitness';
}
