<?php

namespace App\Services\Requisitions;

use App\Models\DistanceChart;

class InterCityDistanceService
{
    private $interCityDistanceArray;

    public function __construct()
    {
        $this->interCityDistanceArray = DistanceChart::get();
    }

    /**
     * @return array
     */
    public function getInterCityDistanceArray(): mixed
    {
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
