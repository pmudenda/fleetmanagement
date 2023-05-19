<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Table;

class ChargeOutRate extends Model
{
    use HasFactory;
    protected $table = 'CONFIG_CHARGE_OUT_RATE';
}
