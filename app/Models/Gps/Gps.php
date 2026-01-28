<?php

namespace App\Models\Gps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gps extends Model
{
    use HasFactory;

    protected $table = 'GPS';

    protected $primaryKey = 'IMEI';

    public $incrementing = false;
    protected $appends = ['status_label'];

    protected $keyType = 'string';


    public $timestamps = true;

    protected $fillable = [
        'model',
        'type',
        'imei',
        'serial',
        'reg_number',
        'mobile_number',
        'odometer',
        'connected_at',
        'last_seen_at',
        'status',
        'type_id',
    ];

    protected $casts = [
        'connected_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'type_id'      => 'integer',
        'odometer' => 'integer',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match ((int) $this->status) {
            1       => 'Active',
            0       => 'Inactive',
            default => 'Unknown',
        };
    }
}
