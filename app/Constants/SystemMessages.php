<?php

namespace App\Constants;

class SystemMessages
{
    const valid = "Odometer validated successfully";
    const generalDataProcessed = 'Vehicle General Data Processed Successfully';
    const onboardingComplete = 'Vehicle Onboarded Successfully. You will now be redirected to vehicle Register';

    public static function chargeOutRateAddedSuccessfully(): string
    {
        return "Record Added Successfully";
    }

    public static function accessoriesCheckedIn(): string
    {
        return "Vehicle Accessories Processed Successfully";
    }
}
