<?php

namespace App\Models\reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'ZFM_AREAS_VIEW';

    protected $attributes = [
        'area',
        'description',
        'description_long'
    ];
}
