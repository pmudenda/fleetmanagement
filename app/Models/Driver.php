<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'DM_DRIVER';
    protected $fillable = [
        'name',
        'staff_number',
        'grade',
        'position',
        'location',
        'license_number',
        'license_date_issued',
        'license_date_expiry',
        'license_category',
        'permit_number',
        'permit_date_issued',
        'permit_date_expiry',
        'id_designated',
        //'license_front',
        //'license_back',
        //'permit',
        'on_boarding_reference',
        'status',
        'created_by',
        'modified_by',
        'deleted_at',
        'is_designated_driver'
    ];
}
