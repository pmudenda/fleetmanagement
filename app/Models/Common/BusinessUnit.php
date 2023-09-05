<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{
    protected $table = 'zfm_business_units';
    protected $attributes = [
        'code_bu',
        'description',
        'code_bu_superior',
        'indicator_last_level',
        'status'
    ];
}
