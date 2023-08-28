<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImprestBuyHeader extends Model
{
    use SoftDeletes;

    protected $table = 'wm_imprest_buy_headers';

    protected $fillable = [
        'cost_center',
        'business_unit_code',
        'user_unit_code',
        'user_unit_id',
        'pay_point_id',
        'work_order_number',
        'total_payment',
        'change',
        'code',
        'external_ref_no',
        'zqms_ref_no',
        'status',
        'name',
        'staff_no',
        'claim_date',
        'authorised_by',
        'date_authorised',
        'created_by',
        'deleted_at'
    ];
}
