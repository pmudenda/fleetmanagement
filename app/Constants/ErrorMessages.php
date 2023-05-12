<?php

namespace App\Constants;

class ErrorMessages
{

    const responsibleUserNotActive = "User Responsible for the vehicle is not active. Your requisition can not be processed";
    const vehicleNotActive = "Requisition not accepts while vehicle is not in active state Your requisition can not be processed";
    const requisitionStillActive = "Request failed validation, Previous requisition number @req_no is still Active. Next Request Date Is @date_valid_to";
}
