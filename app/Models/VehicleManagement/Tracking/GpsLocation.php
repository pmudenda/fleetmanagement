<?php

namespace App\Models\VehicleManagement\Tracking;

use App\Models\Security\User;
use App\Models\VehicleManagement\VehicleHeader;
use Illuminate\Database\Eloquent\Model;

class GpsLocation extends Model
{
    protected $with = ['user'];

    public $incrementing = false;
    protected $primaryKey = 'created_at';
    protected $casts = [
        'tracked_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id', 'latitude', 'longitude', 'accuracy', 'altitude', 'heading', 'speed',
        'imei','distance','odometer','angle','fuel','tracked_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
