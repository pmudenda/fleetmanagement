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
        'req_no',
        'reg_no',
        'valid_date_from',
        'valid_date_to',
        'st_pur',
        'odometer',
        'town_from',
        'town_to',
        'date_created',
        'created_by',
        'cost_centre',
        'item_type',
        'workshop_no',
        'document_no',
        'form_order',
        'requested_by',
        'comments'
    ];
}
