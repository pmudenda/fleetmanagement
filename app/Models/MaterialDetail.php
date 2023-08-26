<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialDetail extends Model
{
    use HasFactory;

    protected $table = 'GEN_MATERIAL_DETAILS';
    protected $fillable = [
        'created_by',
        'date_created',
        'req_no',
        'material_code',
        'quantity',
        'unit_of_measure',
        'specifications',
        'description',
        'project_code',
        'project_name',
        'supplier_code',
        'cost_centre',
        'stores_code',
        'cost_centre_name',
        'reg_no',
        'amount',
        'price',
        'ref_no',
        'max_allowed',
        'claimed'
    ];
}
