<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCardHeader extends Model
{
    use HasFactory;

    protected $table = 'WM_JOB_CARD_HEADER';

    protected $fillable = [

        'wshp_act_code',
        'workshop_code',
        'reg_no',
        'job_card_no',
        'req_no',
        'driver_in',
        'repair_type',

        'date_in',
        'time_in',
        'fuel_level_in',
        'sub_fuel_level_in',
        'millage_in',

        'receiving_section',
        'received_by',
        'expected_date_out',
        'section_mid_code',

        'date_out',
        'time_out',
        'fuel_level_out',
        'sub_fuel_level_out',

        'millage_out',
        'dispatching_section',
        'dispatched_by',

        'accident_ref',
        'book_ref',
        'driver_out',
        'repair_cost',

        'odo_next_service',
        'service_due_after',
        'created_by',
        'modified_by',
        'status',
        'driver_acknowledged',
        'date_acknowledged',
        'step'
    ];
}
