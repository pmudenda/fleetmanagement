<?php

namespace App\Models\configurations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory;

    protected $table = 'CONFIG_ACCESSORIES';

    protected $fillable = [
        'created_by',
        'name',
        'code',
        'status'
    ];

}
