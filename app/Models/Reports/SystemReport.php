<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemReport extends Model
{

    protected $casts = [
        'filters' => 'array',
        'aggregates' => 'array'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('menuOrder', function (Builder $builder) {
            $builder->orderBy('menu_order');
        });
    }
}
