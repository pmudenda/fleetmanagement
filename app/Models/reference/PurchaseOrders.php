<?php

namespace App\Models\reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrders extends Model
{
    use HasFactory;
    protected $table = 'PURCHASE_ORDERS_ONBOARDING_V';

    protected $fillable = [
        'document_no',
        'code_supplier',
        'name_of_supplier'
    ];
}
