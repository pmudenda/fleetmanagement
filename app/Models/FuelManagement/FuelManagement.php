<?php

namespace App\Models\FuelManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelManagement extends Model
{
    protected $table = 'VM_FUEL_ISSUE';

    protected $fillable = [
        'created_by',
        'voucher_no',
        'voucher_date',
        'voucher_time',
        'document_no',
        'reg_no',
        'cost_center',
        'area_code',
        'authorised_by',
        'received_by',
        'issue_office',
        'unit_of_measure',
        'fuel_code',
        'quantity',
        'price',
        'amount',
        'odometer',
        'pump_start',
        'pump_end',
        'status',
        'business_unit',
        'user_unit',
        'system_origin',
        'requisition_type',
        'justification',
    ];
}
