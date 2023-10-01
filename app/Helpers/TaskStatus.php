<?php

namespace App\Helpers;

class TaskStatus
{
    public static function submitted(): string
    {
        return config('status.submitted', '99');
    }

    public static function sentBack(): string
    {
        return config('status.sentBack', '99');
    }
}
