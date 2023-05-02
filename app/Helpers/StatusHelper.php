<?php

namespace App\Helpers;

class StatusHelper
{
    public static function Submitted(): string
    {
        return '021';
    }

    public static function PendingVerification(): string
    {
        return "021";
    }

    public static function active(): string
    {
        return "01";
    }

    public static function approved(): string
    {
        return "022";
    }

    public static function new(): string
    {
        return  "021";
    }


}
