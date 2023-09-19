<?php

namespace App\Services\VehicleManagement;

use App\Exceptions\DataNotFoundException;
use App\Models\VehicleManagement\VehicleHeader;
use Illuminate\Support\Facades\Log;

class OdometerValidationService
{

    /**
     * @throws DataNotFoundException
     */
    public function validate(string $vehicleRegistration, mixed $userProvidedOdometer): bool
    {
        $vehicle = VehicleHeader::where('registration_number', '=', $vehicleRegistration)->first();
        Log::debug("Validating Odometer");
        Log::debug("Usr $userProvidedOdometer vs on Vehicle $vehicle->mileage");

        if (empty($vehicle)) {
            throw new DataNotFoundException("Vehicle not found");
        }

        return $userProvidedOdometer >= $vehicle->mileage;
    }
}
