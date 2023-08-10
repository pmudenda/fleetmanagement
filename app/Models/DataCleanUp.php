<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataCleanUp extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'TMS_DATA_CLEAN_UP';
    protected $fillable = [
        'registrationNumber',
        'vehicle_status',
        'type_brand_model',
        'current_assignation',
        'current_vehicle_operator',
        'current_vehicle_supervisor',
        'organizationalUnit',
        'operator',
        'operatorId',
        'supervisor',
        'supervisorId',
        'created_by',
        'updated_by',
        'justification',
        'deleted_at'
    ];
}
