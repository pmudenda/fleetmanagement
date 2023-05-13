<?php

namespace App\Constants;

class ErrorMessages
{
    const responsibleUserNotActive = "User Responsible for the vehicle is not active. Your requisition can not be processed";
    const vehicleNotActive = "Requisition not accepts while vehicle is not in active state Your requisition can not be processed";
    const requisitionStillActive = "Request failed validation, Previous requisition number @req_no is still Active. Next Request Date Is @date_valid_to";
    const internalServerError = 'We could not complete processing your request due to an error';

    public static function invalidCurrentOdometerReading(): string
    {
        return "Invalid Current odometer, The value is less that the initial odometer reading";
    }
}
