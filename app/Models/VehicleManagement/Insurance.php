<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $table = 'vm_insurance';

    public $fillable = [
        'reg_no',
        'policy_no',
        'period_from',
        'period_to',
        'insured_amount',
        'premium',
        'payment_date',
        'certificate_number',
        'insurance_sub_type',
        'created_by',
        'modified_by',
        'deleted_at',
    ];

    protected $casts = [
        'period_from' => 'date:Y-m-d',
        'period_to' => 'date:Y-m-d',
        'payment_date' => 'date:Y-m-d',
    ];
}
