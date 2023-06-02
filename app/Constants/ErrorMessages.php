<?php

namespace App\Constants;

class ErrorMessages
{
    const employeeNotFound = "Employee Not Found, Please check the Staff Number and try again";
    const driverNotFound = 'Driver with staff number @input was not found. Verify the input and ensure the employee was registered as an authorised driver.';
    const driversLicenceExpired = "Driver with staff number @input has expired Driver's License Number";

    const driverPermitExpired = "Driver with staff number @input has expired Driver's Permit";
    const overrideRequisitionWithoutPriorRequisition = "Override requisition not permitted without prior Requisition";


    public static function invalidCurrentOdometerReading(): string
    {
        return "Invalid Current odometer, The value is less that the initial odometer reading";
    }

    public static function vehicleHasActiveRequisition(): string
    {
        return 'An active requisition @req_no exists for this vehicle @veh_reg. Next Request Date Is @date_valid_to, Please Contact Fleet Master
                            System Administrator on 3309,3350,3351,3306,3307, fleetmaster@zesco.co.zm.  for technical assistance';
    }


    public static function getMessage(string $errorCode): string
    {
        //vehicleHasActiveRequisition
        return config('error_message.' . $errorCode);
    }
}
