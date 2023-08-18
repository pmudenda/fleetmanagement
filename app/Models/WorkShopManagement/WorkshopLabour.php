<?php

namespace App\Models\WorkShopManagement;

use Illuminate\Database\Eloquent\Model;

class WorkshopLabour extends Model
{
    protected $table = 'wm_workshop_labours';
    protected $fillable = [
        'wshp_act_code',
        'wshp_code',
        'section',
        'evaluation',
        'date_lab',
        'mechanic',
        'hours_worked',
        'rate',
        'total_amount',
        'def_no',
        'defect_id',
        'created_by',
        'authorised_by',
        'type_of_hour',
        'job_card_instruction'
    ];
}
