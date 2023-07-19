<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accident extends Model
{
    protected $table = 'VM_ACCIDENT';
    protected $fillable = [
        'reference',
        'area',
        'vehicle_reg_no',
        'driver',
        'date_of_accident',
        'time_of_accident',
        'date_reported',
        'time_reported',
        'nature_of_accident',
        'type_of_accident',
        'guilty',
        'location',
        'death',
        'num_passengers',
        'mileage',
        'other_people_involved',
        'day_of_week',
        'other_vehicle_involved',
        'property',
        'vehicle_insured',
        'driver_experience',
    ];
    use HasFactory;
}
