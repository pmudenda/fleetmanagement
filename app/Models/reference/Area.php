<?php

namespace App\Models\reference;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'SPMS_AREAS_VIEW';

    protected $attributes = [
        'area',
        'description',
        'description_long'
    ];
}
