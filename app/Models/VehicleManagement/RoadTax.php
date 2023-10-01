<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoadTax extends Model
{
    use SoftDeletes;

    protected $table = 'vm_road_tax';
    protected $fillable = [
        'reg_no',
        'licence_no',
        'valid_from',
        'valid_to',
        'cost',
        'payment_date',
        'order_no',
        'created_by',
        'modified_by',
        'deleted_at',
    ];
}
