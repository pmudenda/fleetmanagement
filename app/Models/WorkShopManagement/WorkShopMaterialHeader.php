<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShopMaterialHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_type_code',
        'workshop_reference',
        'workshop_code',
        'request_date',
        'collection_date',
        'supplier_code',
        'purchasing_office',
        'job_card_no'
    ];
}
