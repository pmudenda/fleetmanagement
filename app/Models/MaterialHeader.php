<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialHeader extends Model
{
    use HasFactory;

    protected $table = 'GEN_MATERIAL_HEADERS';

    protected $fillable = [
        'proc_ref',
        'st_pur',
        'req_no',
        'veh_reg_no',
        'cost_centre',
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
        'status',
        'requisition_type',
        'cost_assigned_to',
        'item_type',
        'workshop_no',
        'document_no',
        'form_order'
    ];
}
