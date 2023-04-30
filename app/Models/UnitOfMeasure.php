<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    use HasFactory;
    protected $table = 'CONFIG_UNIT_OF_MEASURES';
    protected $attributes  = [
        'name',
        'short_name',
        'code',
        'status',
        'created_by'
    ];
}
