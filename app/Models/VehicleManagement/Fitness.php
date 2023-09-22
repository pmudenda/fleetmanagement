<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fitness extends Model
{
    use SoftDeletes;
    protected $table = 'vm_fitness';
    protected $fillable = [
        'reg_no',
        'book_number',
        'period_from',
        'period_to',
        'amount',
        'payment_date',
        'created_by',
        'comments',
        'result',
        'modified_by',
        'deleted_at',
    ];
}
