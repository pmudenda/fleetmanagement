<?php

namespace App\Constants;

use Illuminate\Support\Facades\Log;

class ErrorMessages
{
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
