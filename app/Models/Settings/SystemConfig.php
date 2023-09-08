<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    protected $table = 'gen_system_configs';
    protected $fillable = [
        'config_file_name',
        'name',
        'value',
        'status',
    ];
}
