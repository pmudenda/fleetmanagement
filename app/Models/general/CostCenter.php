<?php

namespace App\Models\general;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    use HasFactory;

    protected $table = 'REF_COST_CENTERS';
    protected $attributes = [
        'code_cost_center',
        'description',
        'code_cost_center_superior',
        'indicator_last_level',
        'status',
    ];
}
