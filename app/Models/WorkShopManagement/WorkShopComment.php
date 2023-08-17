<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShopComment extends Model
{
    use HasFactory;

    protected $table = 'WM_WORKSHOP_COMMENTS';

    protected $fillable = [
        'workshop_reference',
        'type',
        'remarks',
        'status',
        'created_by'
    ];
}
