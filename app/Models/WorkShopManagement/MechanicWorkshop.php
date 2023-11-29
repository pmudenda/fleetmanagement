<?php

namespace App\Models\WorkShopManagement;

use App\Enums\IsSupervisor;
use App\Models\Settings\WorkShop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MechanicWorkshop extends Pivot {
    protected $casts = [
        'deleted_at' => 'datetime',
        'is_supervisor' => IsSupervisor::class
    ];

    public function workshop(){
        return $this->belongsTo(WorkShop::class,'work_shop_id','');
    }
}
