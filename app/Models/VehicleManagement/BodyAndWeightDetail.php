<?php

namespace App\Models\VehicleManagement;

use Illuminate\Database\Eloquent\Model;

class BodyAndWeightDetail extends Model
{
    protected $table = 'VM_BODY_AND_WEIGHT_DETAILS';
    protected $fillable = [
        'vehicle_header_id',
        'reg_no',
        'height',
        'length',
        'numberOfSeats',

        'width',
        'grossWeight',
        'tareWeight',

        'seatCapFront',
        'seatCapRear',
        'volumeOfBootTanker',
        'distanceAxle1',
        'distanceAxle2',
        'distanceAxle3',
        'distanceAxle4',
        'trailerWeight2',
        'trailerWeight3',
        'trailerWeight4',

        'created_by',
        'created_name'
    ];
}
