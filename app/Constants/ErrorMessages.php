<?php

namespace App\Constants;

class ErrorMessages
{
    const contact = '3309,3350,3351,3306';
    const email = 'fleetmaster@zesco.com';
    const extension = "3307";
    const responsibleUserNotActive = "User Responsible for the vehicle @reg_no is not active .
     Your requisition can not be processed, Please Contact Fleet Master
                            System Administrator on 3309,3350,3351,3306, fleetmaster@zesco.com";
    const vehicleNotActive = "Requisition not accepted while vehicle is not in active state Your requisition can not be processed, Please Contact Fleet Master
                            System Administrator on " . self::contact . ", " . self::email . " for technical assistance";
    const requisitionStillActive = "Request failed validation, Previous requisition number @req_no is still Active. Next Request Date Is @date_valid_to, Please Contact Fleet Master
                            System Administrator on " . self::contact . ", " . self::email . " for technical assistance";
    public final const internalServerError = 'We could not complete processing your request due to an error. Please Contact Fleet Master
                            System Administrator on ' . self::contact . ', ' . self::email . ', '. self::extension . " for technical assistance";
    const employeeNotFound = "Employee Not Found, Please check the Staff Number and try again";


    public static function invalidCurrentOdometerReading(): string
    {
        return "Invalid Current odometer, The value is less that the initial odometer reading";
    }

    public static function vehicleHasActiveRequisition(): string
    {
        return 'Request failed validation, Vehicle has an open requisition Number @re_no';
    }

    public static function storesRequisitionFailed(): string
    {
        return 'We could not generate stores requisition';
    }
}
