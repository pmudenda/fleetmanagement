<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseType extends Model
{
    use HasFactory;

    protected $table = 'CONFIG_LICENSE_TYPES';
}
