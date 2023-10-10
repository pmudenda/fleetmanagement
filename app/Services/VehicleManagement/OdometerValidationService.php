<?php

namespace App\Services\VehicleManagement;

use App\Constants\QueryComparisonOperator;
use App\Exceptions\DataNotFoundException;
use App\Models\VehicleManagement\VehicleHeader;
use Illuminate\Support\Facades\Log;

class OdometerValidationService
{

    /**
     * @throws DataNotFoundException
     */
    public function validate(string $vehicleRegistration, mixed $userProvidedOdometer)
    {
        $vehicle = VehicleHeader::where('registration_number', QueryComparisonOperator::EQUALS,
            $vehicleRegistration)->first();
        Log::debug("Validating Odometer");
        Log::debug("Usr $userProvidedOdometer vs on Vehicle $vehicle->mileage");

        if (empty($vehicle)) {
            throw new DataNotFoundException("Vehicle not found");
        }

        $variance = ($userProvidedOdometer / $vehicle->mileage) * 100;
//        return $variance;

        return $userProvidedOdometer >= $vehicle->mileage;
    }
}
