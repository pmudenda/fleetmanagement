<?php

namespace App\Enums;

enum WorkflowProcessCodes: string
{
    case NormalFuelRequisition = "2000";
    case OutOfTownFuelRequisition = "2001";
    case OverrideFuelRequisition = "2002";
}
