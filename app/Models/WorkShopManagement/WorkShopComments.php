<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShopComments extends Model
{
    use HasFactory;

    protected $table = 'WM_WORK_SHOP_COMMENTS';

    protected $fillable = [
        'job_card_no',
        'type',
        'remarks',
        'status',
        'created_by'
    ];
}
