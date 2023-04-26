<?php

namespace App\Models\general;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseTypes extends Model
{
    use HasFactory;

    protected $table = 'CONFIG_LICENSE_TYPES';
}
