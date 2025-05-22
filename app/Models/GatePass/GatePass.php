<?php

namespace App\Models\GatePass;

use App\Enums\GatePassStatus;
use App\Enums\GatePassType;
use App\Models\Security\User;
use App\Models\VehicleManagement\VehicleHeader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GatePass extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $casts = [
        'expires_at' => 'datetime',
        'departure_at' => 'datetime',
        'status' => GatePassStatus::class,
        'type' => GatePassType::class,
        'authorised_at' => 'datetime',
        'checked_at' => 'datetime',
    ];

    protected $fillable = [
        'type',
        'reg_no',
        'expires_at',
        'purpose',
        'departure_at',
        'departure_town',
        'destination_town',
        'authorised_at',
        'authorised_by',
        'checked_by',
        'checked_at',
        'status',
        'user_id',
        'authorised_reason',
        'checked_reason',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function vehicle() {
        return $this->belongsTo(VehicleHeader::class,'reg_no','registration_number');
    }

    public function authorisedBy(){
        return $this->belongsTo(User::class,'authorised_by');
    }

    public function checkedBy(){
        return $this->belongsTo(User::class,'checked_by');
    }

    public function getRouteKeyName() {
        return 'reference_number';
    }
}
