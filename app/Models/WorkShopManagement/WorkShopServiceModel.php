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
        'wshp_code',
        'section',
        'req_evaluation',
        'date_send',
        'date_collect',
        'supplier_code',
        'supplier_name',
        'unit_of_measure',
        'amount_est',
        'price',
        'def_no',
        'office_code',
        'specification',
        'ind',
        'mat_code',
        'quantity',
        'stf_number',
        'movt_no',
        'status',
        'originator',
        'requested_by_id',
        'authorised_by',
        'created_by',
        'modified_by',
        'fech_act',
        'user1',
    ];
}
