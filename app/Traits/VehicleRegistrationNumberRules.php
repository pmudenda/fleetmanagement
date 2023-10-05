<?php

namespace App\Traits;

trait VehicleRegistrationNumberRules
{
    /**
     * Run the validation rule.
     *
     * @return array
     */
    public function vehicleRegistrationNumber(): array
    {
        return
            [
                'required',
                'string',
                'max:10',
                'exists:App\Models\VehicleManagement\VehicleHeader,registration_number'
            ];
    }
}
