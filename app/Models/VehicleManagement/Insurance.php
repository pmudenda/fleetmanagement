<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insurance extends Model
{
    use SoftDeletes;
    protected $table = 'vm_insurance';
}
