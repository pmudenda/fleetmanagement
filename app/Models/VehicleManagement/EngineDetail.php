<?php

namespace App\Models\VehicleManagement;

use App\Models\Reference\Article;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EngineDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'VM_ENGINE_DETAILS';
    protected $fillable = [
        'actual_engine_power',
        'claimed_engine_power',
        'engine_brand',
        'engine_capacity',
        'engine_type',
        'fuel_allocation',
        'fuel_consumption',
        'fuel_types',
        'num_batteries',
        'number_of_cylinders',
        'tank_capacity',
        'sub_tank_capacity',
        'transmission_type',
        'battery_brand',
        'battery_size',
        'battery_power',
        'front_tyre_size',
        'number_of_tyres',
        'rear_tyre_size',
        'tyre_brand',
        'vehicle_header_id',
        'created_by',
        'created_name'
    ];

    public function fuelType() {
        return $this->belongsTo(Article::class, 'fuel_types','code_article');
    }
}
