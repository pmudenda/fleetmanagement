<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuelAllocation extends Model
{
    use SoftDeletes;
    protected $table = 'vm_fuel_allocations';
    protected $fillable = [
        'created_by',
        'modified_by',
        'allocation_amount',
        'period_from',
        'period_to',
        'status',
        'reg_no',
        'user_update',
        'valid_for',
        'balance',
        'justification'
    ];
}
