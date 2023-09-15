<?php

namespace App\Enums;

enum InsuranceState: string
{
    case Valid = 'valid';
    case Expired = 'expired';
}
