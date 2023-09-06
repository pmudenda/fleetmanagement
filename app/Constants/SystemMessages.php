<?php

namespace App\Constants;

class SystemMessages
{
    const ODOMETER_VALIDATED_SUCCESSFULLY = "Odometer validated successfully";
    const VEHICLE_GENERAL_DATA_PROCESSED_SUCCESSFULLY = 'Vehicle General Data Processed Successfully';
    const VEHICLE_ONBOARDED_SUCCESSFULLY =
        'Vehicle Onboarded Successfully. You will now be redirected to vehicle Register';
    const REQUEST_PROCESSED_SUCCESSFULLY =
        'Your request has been processed  Successfully, Click ok to proceed with onboarding process';
    const TECHNICAL_DATA_SAVED = 'Vehicle Technical Data Processed Successfully';
    const ARTICLES_ATTACHED_SUCCESSFULLY = 'Article(s) Attached Successfully';
    const NORMAL_REQUISITION_RAISED
        = "CANCELLED BECAUSE THE VEHICLE HAS BEEN EXITED FROM THE WORKSHOP IN FLEET MASTER.";
    const EXIT_FROM_WORKSHOP = "CANCELLED BECAUSE THE VEHICLE HAS BEEN EXITED FROM THE WORKSHOP IN FLEET MASTER.";
    const OUT_OF_TOWN_REQUISITION_RAISED
        = "CANCELLED BECAUSE OUT OF TOWN REQUISITION HAS BEEN REQUESTED IN FLEET MASTER.";
    const TOM_CARD_ASSIGNED = "Tom Card Assigned Successfully";
    const TOM_CARD_ASSIGNMENT_FAILED = "Tom Card Assignment Failed";
    const TOM_CARD_REVOCATION_FAILED = "Tom Card Revokation Failed";
    const TOM_CARD_REVOKED = "Tom Card Revoked Successfully";

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
            "The vehicle @reg has not completed the onboarding process.
            Please Contact Fleet Master System Administrator on 3309,3350,3351,3306, fleetmaster@zesco.co.com for assistance";
    }

    public static function vehicleInWorkshop(): string
    {
        return "The vehicle @reg is in the @workshop Please Contact Fleet Master System Administrator on
        3309,3350,3351,3306, fleetmaster@zesco.co.com for assistance";
    }

    public static function vehicleInNotActive(): string
    {
        return "The vehicle @reg is in @state. Please Contact Fleet Master System Administrator on
        3309,3350,3351,3306, fleetmaster@zesco.co.com for assistance";
    }

    public static function userUpdateFailed(): string
    {
        return 'User Details Failed to Updated!';
    }

    public static function userUpdateSuccessful(): string
    {
        return 'User Details Updated Successfully';
    }

    public static function roleAssignedSuccessful(): string
    {
        return 'Role Successfully detached..';
    }

    public static function userCreateSuccessful(): string
    {
        return 'User Defined successfully..';
    }
}
