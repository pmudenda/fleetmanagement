<?php

namespace App\Helpers;

class Priority
{
    public static function high(): string
    {
        return 'High';
    }

    public static function low(): string
    {
        return 'Low';
    }

    public static function medium() : string
    {
        return 'Medium';
    }
}
