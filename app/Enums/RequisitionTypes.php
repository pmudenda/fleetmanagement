<?php

namespace App\Enums;

enum RequisitionTypes: string
{
    case Normal = "010";
    case OutOfTown = "011";

    case Override = "012";

}
