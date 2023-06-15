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

    public static function defectRecorded(): string
    {
        return "Defect Submission Successful";
    }

    public static function materialAndServicesRecorded(): string
    {
        return "Submission Successful";
    }

    public static function vehiclePendingOnboardingCompletion(): string
    {
        return
            "The vehicle @reg has not completed the onboarding process. Please Contact Fleet Master
                            System Administrator on 3309,3350,3351,3306, fleetmaster@zesco.com";
    }

    public static function vehicleInWorkshop(): string
    {
        return
            "The vehicle @reg is in Workshop. Please Contact Fleet Master
                            System Administrator on 3309,3350,3351,3306, fleetmaster@zesco.com";
    }
}
