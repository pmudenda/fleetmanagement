<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    protected $table = 'zfm_cost_centers';
    protected $attributes = [
        'code_cost_center',
        'description',
        'code_cost_center_superior',
        'indicator_last_level',
        'status',
    ];
}
