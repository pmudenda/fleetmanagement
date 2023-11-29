<?php

namespace App\Services\Security;

use Illuminate\Support\Facades\Crypt;

class ParameterEncryption
{
    /**
     * @param $input
     * @return string
     */
    public static function encrypt($input): string
    {
        return Crypt::encryptString($input);
    }

    /**
     * Decrypts tokens
     * @param $encryptedValue
     * @return string
     */
    public static function decrypt($encryptedValue): string
    {
        return Crypt::decryptString($encryptedValue);
    }
}
