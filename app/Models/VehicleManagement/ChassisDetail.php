<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChassisDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'VM_CHASSIS_DETAILS';
    protected $fillable = [
        'chassis_number',
        'date_on_road',
        'engine_number',
        'initial_odometer_reading',
        'current_odometer_reading',
        'inspection_date',
        'lst_service_odometer_reading',
        'nxt_service_odometer_reading',
        'odometer_reset',
        'registration_date',
        'min_req_driving_license',
        'status',
        'sticker_registration_number',
        'vehicle_charge_out_rate',
        'white_book_serial',
        'year_of_manufacture',
        'created_by',
        'created_name',
        'vehicle_header_id'
    ];
}
