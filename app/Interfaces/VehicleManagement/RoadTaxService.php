<?php

namespace App\Interfaces\VehicleManagement;

interface RoadTaxService
{
    function getRoadLicence(mixed $registrationNumber): array;
}
