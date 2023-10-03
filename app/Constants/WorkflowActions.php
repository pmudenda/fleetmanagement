<?php

namespace App\Constants;
class WorkflowActions
{
    const APPROVE = 'approve';
    const REJECT = 'reject';
    const SEND_BACK = 'send_back';
    const RESUBMIT = 'resubmit';

    const CANCEL = 'cancel';

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

    public static function resubmit(): int
    {
        return 5;
    }

    public static function cancel(): int
    {
        return 6;
    }
}
