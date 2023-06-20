<?php

namespace App\Models\reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $table = 'P_ORDERS_ONBOARDING_VIEW';

    protected $fillable = [
        'document_no',
        'code_supplier',
        'name_of_supplier',
        'po_status_description',
        'status_code'
    ];
}
