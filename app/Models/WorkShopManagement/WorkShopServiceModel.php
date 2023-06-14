<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShopServiceModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'workshop_reference',
        'workshop_code',
        'section',
        'req_evaluation',
        'date_send',
        'material_code',
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
