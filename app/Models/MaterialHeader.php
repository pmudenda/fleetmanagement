<?php

namespace App\Models;

use App\Models\Security\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialHeader extends Model
{
    protected $table = 'GEN_MATERIAL_HEADERS';

    protected $fillable = [
        'proc_ref',
        'st_pur',
        'req_no',
        'veh_reg_no',
        'cost_centre',
        'user_unit',
        'valid_date_from',
        'valid_date_to',
        'odometer',
        'town_from',
        'town_to',
        'date_created',
        'created_by',
        'requested_by_id',
        'requested_by',
        'comments',
        'is_fuel',
        'status',
        'requisition_type',
        'cost_assigned_to',
        'item_type',
        'workshop_no',
        'document_no',
        'form_order',
        'supplier_code',
        'store',
        'purchase_office',
        'issue_balance', // holds issue balance when there is partial issue
        'project_name'
    ];

    public function originator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by', 'staff_no' )->withDefault();;
    }
}
