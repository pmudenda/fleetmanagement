<?php

namespace App\Models\vehiclemanagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostAndValuation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'VM_COST_AND_VALUATIONS';
    protected $fillable = [
        'assetNumber',
        'bookValue',
        'costOfLicense',
        'costPrice',
        'premium',
        'supplierName',
        'yearOfPurchase',
        'created_by',
        'created_name',
        'vehicle_header_id',
        'purchase_order_document'
    ];
}
