<?php

namespace App\Interfaces\VehicleManagement;

interface InsuranceService
{
    function getCheckInsurance(mixed $registrationNumber): array;
}
