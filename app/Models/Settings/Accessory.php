<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    protected $table = 'CONFIG_ACCESSORIES';

    protected $fillable = [
        'created_by',
        'name',
        'code',
        'status'
    ];

}
