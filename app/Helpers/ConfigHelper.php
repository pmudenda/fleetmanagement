<?php

namespace App\Helpers;

class ConfigHelper
{
    const current_login_false = 0;
    const current_login_true = 1;

    public static function currentLoginFalse(): bool
    {
        return self::current_login_false;
    }

    public static function currentLoginTrue(): bool
    {
        return self::current_login_true;
    }
}
