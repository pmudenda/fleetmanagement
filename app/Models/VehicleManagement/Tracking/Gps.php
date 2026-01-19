<?php

namespace App\Models\VehicleManagement\Tracking;

use App\Enums\GpsStatus;
use App\Models\VehicleManagement\EngineDetail;
use App\Models\VehicleManagement\VehicleHeader;
use Illuminate\Database\Eloquent\Model;

class Gps extends Model
{

    protected $primaryKey = 'imei';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
//        'status' => GpsStatus::class,
        'connected_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function locations(){
        return $this->hasMany(GpsLocation::class);
    }

    public function lastLocation(){
        return $this->hasOne(GpsLocation::class)->latestOfMany('tracked_at');
    }

    public function vehicle() {
        return $this->belongsTo(VehicleHeader::class,'reg_number','registration_number');
    }

    public function engineDetail() {
        return $this->belongsTo(EngineDetail::class,'reg_number','reg_no');
    }





}
