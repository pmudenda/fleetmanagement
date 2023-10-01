<?php

namespace App\Helpers;

class StringUtils
{

    public static function camelCaseToWords(string $value): string
    {
        return ucwords(join(" ", (preg_split('/(?=[A-Z])/', $value))));
    }

}
