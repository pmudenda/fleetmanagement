<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'config_currencies';

    protected $fillable = [
        'created_by',
        'currency_code',
        'abbreviation',
        'description',
        'country_code',
    ];
}
