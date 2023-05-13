<?php

namespace App\Constants;

class ErrorMessages
{
    const responsibleUserNotActive = "User Responsible for the vehicle @reg_no is not active .
     Your requisition can not be processed, Please Contact Fleet Master
                            System Administrator on 3309,3350,3351,3306, fleetmaster@zesco.com";
    const vehicleNotActive = "Requisition not accepted while vehicle is not in active state Your requisition can not be processed, Please Contact Fleet Master
                            System Administrator on 3309,3350,3351,3306, fleetmaster@zesco.com";
    const requisitionStillActive = "Request failed validation, Previous requisition number @req_no is still Active. Next Request Date Is @date_valid_to, Please Contact Fleet Master
                            System Administrator on 3309,3350,3351,3306, fleetmaster@zesco.com for technical assistance";
    const internalServerError = 'We could not complete processing your request due to an error';

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
