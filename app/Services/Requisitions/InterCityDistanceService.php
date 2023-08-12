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
        $interCityDistanceArray = cache()->remember('business_units', $timeToLive, function () {
            return DistanceChart::orderBy('town_from')->get();
        });

        return $this->interCityDistanceArray;
    }

    public function getDistance($from, $to): mixed
    {
        //$result = $this->interCityDistanceArray[$from];
        return DistanceChart::where('town_from', $from)
            ->where('town_to', $to)
            ->first()->distance;
    }

}
