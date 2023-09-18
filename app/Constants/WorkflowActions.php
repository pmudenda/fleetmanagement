<?php

namespace App\Constants;
class WorkflowActions
{
    const approve = 'approve';
    const reject = 'reject';
    const sendBack = 'send_back';
    const resubmit = 'resubmit';

    public static function submit(): int
    {
        return 1;
    }

    public static function approve(): int
    {
        return 3;
    }

    public static function reject(): int
    {
        return 2;
    }

    public static function sendBack(): int
    {
        return 0;
    }

    public static function resubmitted(): int
    {
        return 5;
    }
}
