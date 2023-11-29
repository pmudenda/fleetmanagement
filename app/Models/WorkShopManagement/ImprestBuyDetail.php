<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImprestBuyDetail extends Model
{
    use SoftDeletes;

    protected $table = 'wm_imprest_buy_details';
    protected $fillable = [
        'header_reference',
        'vehicle_registration',
        'material_code',
        'description',
        'specification',
        'quantity',
        'unit_of_measure',
        'unit_price',
        'total_price',
        'created_by',
        'deleted_at'
    ];
}
