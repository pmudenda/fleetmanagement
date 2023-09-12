<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShopTable extends Model
{
    protected $table = 'WM_WORKSHOP_TABLES';

    protected $fillable =[
        'type_code',
        'parent',
        'code',
        'status',
        'description',
    ];
}
