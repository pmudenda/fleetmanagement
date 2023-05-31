<?php

namespace App\Models\Workflow;
class WorkflowActions
{
    public static function submit(): int
    {
        return 1;
    }

    public static function approve(): int
    {
        return 3;
    }

    public static function rejected(): int
    {
        return 2;
    }

    public static function sendBack(): int
    {
        return 0;
    }
}
