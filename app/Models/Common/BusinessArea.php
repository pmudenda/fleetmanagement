<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessArea extends Model
{
    use HasFactory;
    protected $table = 'CONFIG_BUSINESS_AREAS';
    protected $fillable = [
        'code',
        'name',
    ];
}
