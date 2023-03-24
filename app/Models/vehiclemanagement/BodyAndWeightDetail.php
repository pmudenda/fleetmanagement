<?php

namespace App\Models\vehiclemanagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BodyAndWeightDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'VM_BODY_AND_WEIGHT_DETAILS';
    protected $fillable = [
        'distanceAxle1',
        'distanceAxle2',
        'distanceAxle3',
        'distanceAxle4',
        'height',
        'length',
        'numberOfSeats',
        'seatCapFront',
        'seatCapRear',
        'volumeOfBootTanker',
        'width',
        'grossWeight',
        'tareWeight',
        'trailerWeight2',
        'trailerWeight3',
        'trailerWeight4',
        'vehicle_header_id'
    ];
}
