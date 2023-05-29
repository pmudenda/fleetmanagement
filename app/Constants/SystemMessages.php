<?php

namespace App\Constants;

class SystemMessages
{

    const valid = "Odometer validated successfully";
    const InvalidOdometer = "Vehicle Odometer reading failed validation, Current odometer can not be less than the initial odometer value";
    const generalDataProcessed = 'Vehicle General Data Processed Successfully';
    const onboardingComplete = 'Vehicle Onboarded Successfully. You will now be redirected to vehicle Register';

    public static function chargeOutRateAddedSuccessfully(): string
    {
        return "Record Added Successfully";
    }
}
