<?php

namespace App\Enums;

enum ApprovalStage: string
{
    case full = 'fullyAuthorised';

    case partial = 'partiallyAuthorised';
    case sendBack = 'sendBack';
    case resubmit = 'resubmitted';
}
