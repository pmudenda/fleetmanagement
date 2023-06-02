<?php

namespace App\Constants;

class ErrorMessages
{
    public static function getMessage(string $errorCode): string
    {
        //vehicleHasActiveRequisition
        return config('error_message.' . $errorCode);
    }
}
