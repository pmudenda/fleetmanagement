<?php

namespace App\Models\Settings\general;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;

    protected $table = 'CONFIG_STATUSES';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'active',
        'code',
        'color_code',
        'module',
        'created_by',
        'deleted_at',
    ];

}
