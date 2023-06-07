<?php

namespace App\Models\reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOffice extends Model
{
    use HasFactory;

    protected $table = 'ZFM_PURCHASE_OFFICES';

    protected $attributes = [
        'area',
        'description',
        'code_office'
    ];
}
