<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Town extends Model
{
    use SoftDeletes;

    protected $table = 'config_towns';
    protected $fillable = [
        'town_name',
        'town_code',
        'created_by',
        'modified_by',
        'deleted_at',
    ];
}
