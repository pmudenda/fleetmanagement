<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemError extends Model
{
    use HasFactory;

    protected $table = 'CONFIG_SYS_ERROR_MESSAGES';

    protected $fillable = [
        'error_code',
        'error_message',
        'error_type',
        'created_by',
        'deleted_at',
    ];
}
