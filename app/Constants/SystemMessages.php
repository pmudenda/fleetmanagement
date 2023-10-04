<?php

namespace App\Constants;

class SystemMessages
{
    const SERVICE_DESK_MESSAGE = "Please Contact Fleet Master System Administrator on 3309,3350,3351,3306,
            fleetmaster@zesco.co.com for assistance";
    const RECORD_NOT_FOUND = "Record Not Found";
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

    const PURCHASE_ORDER_RETRIEVED = 'Purchase Order Data Retrieved Successfully';
    const REQUISITION_RAISED = "Reservation @ref Submitted Successfully. Task generated for Authorisation";
    const DUPLICATE_ARTICLE = "Article @article has been already selected for vehicle @reg. Check your article";
    const USER_NOT_VERIFIED
        = "User Verification with PHCMS Failed.";
    const PERMISSIONS_ATTACHED = 'Permissions Assigned Successfully..';
    const PERMISSIONS_DETACHED = 'Permissions Successfully detached..';
    const USER_CREATION_FAILED = "User Failed to be created because of an error";

    const MECHANIC_ONBOARDED = "Mechanic Onboarded Successfully";

    const USER_ONBOARDING_SUCCESS = 'User Defined successfully..';
    const REQUISITION_SUCCESSFUL = "Requisition @req Generated and submitted to the next authority for Authorisation";
    const ACCIDENT_REPORT_FOUND = 'Accident Report Found';
    const ACCIDENT_REPORT_NOT_FOUND = 'No Accident Report Found for Vehicle @reg';
    const DELEGATION_CANCELLED = 'Delegation Cancelled Successfully';
    const DELEGATION_NOT_FOUND = "No Active Delegation Found";
    const ORGNIZATIONAL_UNIT_INACTIVE
        = "Organization Unit with Business Unit @bu and Cost Center @cc Is Not Active" . self::SERVICE_DESK_MESSAGE;
    const FUEL_ALLOCATION_SET = "Fuel Allocation Set Successfully";
    const FUEL_ALLOCATION_FAILED = "Could not Set Fuel Allocation on Vehicle";
    const JOBCARD_TASKS_ASSIGNMENTS = "Work Assignments Saved Successfully";


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
            "The vehicle @reg has not completed the onboarding process." . self::SERVICE_DESK_MESSAGE;
    }

    public static function vehicleInWorkshop(): string
    {
        return "The vehicle @reg is in the @workshop "
            . self::SERVICE_DESK_MESSAGE;
    }

    public static function userUpdateFailed(): string
    {
        return 'Details Update Failed!';
    }

    public static function userUpdateSuccessful(): string
    {
        return 'Details Updated Successfully';
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
