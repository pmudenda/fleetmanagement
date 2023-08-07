<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    protected $table = 'wm_mechanics';
    protected $fillable = [
        'staff_no',
        'name',
        'workshop_code',
        'section_code',
        'status',
        'created_by'
    ];


}
