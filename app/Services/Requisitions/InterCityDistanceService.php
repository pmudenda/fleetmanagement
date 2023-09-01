<?php

namespace App\Services\Requisitions;

use App\Models\DistanceChart;

class InterCityDistanceService
{
    /**
     * @return array
     */
    public function getInterCityDistanceArray(): mixed
    {
        return DistanceChart::orderBy('town_to')->get();
    }

    public function getDistance($from, $to): mixed
    {
        return DistanceChart::where('town_from', $from)
            ->where('town_to', $to)
            ->first()->distance;
    }

}
