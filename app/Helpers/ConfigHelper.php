<?php

namespace App\Helpers;

class ConfigHelper
{
    const current_login_false = 0;
    const current_login_true = 1;

    const password_not_changed = 0;
    const password_changed = 1;

    public static function currentLoginFalse(): bool
    {
        return self::current_login_false;
    }

    public static function currentLoginTrue(): bool
    {
        return self::current_login_true;
    }

    public static function passwordNotChanged(): bool
    {
        return self::password_not_changed;
    }

    public static function passWordChanged(): bool
    {
        return self::password_changed;
    }
}
