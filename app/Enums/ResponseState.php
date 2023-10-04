<?php

namespace App\Enums;

enum ResponseState: string
{
    case SUCCESS = 'success';
    case FAILURE = 'failure';
}
