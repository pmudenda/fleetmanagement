<?php

namespace App\Constants;

use Illuminate\Support\Facades\Log;

class ErrorMessages
{
    const INVALID_CHASSIS_NUMBER =
        'The Chassis Number @docNumber has already been registered';
    const INVALID_MVC_NUMBER
        = 'Vehicle with  White Book Serial @docNumber has already been registered';
    const INVALID_ENGINE_NUMBER
        = 'Vehicle with  Engine @docNumber has already been registered';

    public static function getMessage(string $errorCode): string
    {
        try {
            return config('error_message.' . $errorCode) ?? "Error Message Not Found";
        } catch (\Exception $e) {
            Log::error($e);
            return "";
        }
    }
}
