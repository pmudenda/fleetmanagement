<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShopMaterial extends Model
{
    use HasFactory;

    protected $table = 'WM_WORKSHOP_MATERIALS';

    protected $fillable = [
        'wshp_act_code',
        // 'workshop_reference',
        'workshop_code',
        'section',
        'evaluation',
        'date_mat',
        'mat_code',
        'unit_of_measure',
        'quantity',
        'amount',
        'price',
        'defect_no',
        'specifications',
        'proc_ref',
        'st_pur',
        'form_order',
        'store_code',
        'ind',
        'sch_flouted',
        'supplier_code',
        'veh_reg_no',
        'requested_by',
        'requested_by_id',
        'authorised_by',
        'status',
        'created_by',
        'modified_by',
    ];
}
