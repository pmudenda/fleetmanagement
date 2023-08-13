<?php

namespace App\Services\Requisitions;

use App\Models\DistanceChart;

class InterCityDistanceService
{
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getInterCityDistanceArray(): mixed
    {
        //$interCityDistanceArray = collect([]);
        $timeToLive = 60 * 60 ;//* 24 * 30;
       /* return cache()->remember('business_units', $timeToLive, function () {
            return ;
        });*/
        return DistanceChart::orderBy('town_to')->get();
    }

    public function getDistance($from, $to): mixed
    {
        return DistanceChart::where('town_from', $from)
            ->where('town_to', $to)
            ->first()->distance;
    }

}
