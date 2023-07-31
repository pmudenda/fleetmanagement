<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShopServiceModel extends Model
{
    use HasFactory;

    protected $table = 'WM_WORKSHOP_SERVICES';

    protected $fillable = [
        'wshp_act_code',
        //'workshop_reference',
        //'workshop_code',
        'wshp_code',
        'section',
        'req_evaluation',
        'date_send',
        'date_collect',
        'supplier_code',
        'unit_of_measure',
        'amount_est',
        'price',
        'def_no',
        'office_code',
        'specification',
        'ind',
        //'material_code',
        'mat_code',
        'quantity',
        'stf_number',
        //'movement_no',
        'movt_no',
        'status',
        'originator',
        'requested_by_id',
        'authorised_by',
        'created_by',
        'modified_by',
        'fech_act',
        'user1',

        /*'proc_ref',
        'st_pur',
        'form_order',
        'store_code',
        'sch_flouted',
        'veh_reg_no',*/
    ];
}
