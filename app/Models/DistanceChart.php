<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistanceChart extends Model
{
    use SoftDeletes;

    protected $table = 'config_distances_chart';
    protected $fillable = [
        'town_from',
        'town_to',
        'distance',
        'created_by',
        'modified_by',
        'deleted_at'
    ];
}
