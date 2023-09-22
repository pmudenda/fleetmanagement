<?php

namespace App\Enums;

enum DocumentState: string
{
    case Valid = 'valid';
    case Expired = 'expired';
}
