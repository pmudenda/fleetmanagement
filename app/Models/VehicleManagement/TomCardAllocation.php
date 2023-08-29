<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Model;

class TomCardAllocation extends Model
{
    protected $table = 'vm_tom_card_allocations';
    protected $fillable = [
        'reg_no',
        'card_number',
        'period_from',
        'period_to',
        'status',
        'justification',
        'assigned_by',
        'modified_by',
    ];
}
