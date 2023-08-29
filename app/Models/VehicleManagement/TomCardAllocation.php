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
        'assignment_justification',
        'assigned_by',
        'revoked_by',
        'date_revoked',
        'modified_by',
        'revocation_justification'
    ];
}
