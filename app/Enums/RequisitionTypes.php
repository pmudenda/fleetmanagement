<?php

namespace App\Enums;

enum RequisitionTypes: string
{
    case Normal = "10";
    case OutOfTown = "20";

    case Override = "30";

}
