<?php

namespace App\Models\configurations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShop extends Model
{
    use HasFactory;

    protected $table = 'CONFIG_WORKSHOP';

    protected $fillable = [
        'workshop_code',
        'workshop_name',
        'status',
        'area_code',
        'cost_center',
        'user_unit',
        'business_unit',
    ];
}
