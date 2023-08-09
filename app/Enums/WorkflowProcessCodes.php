<?php

namespace App\Enums;

enum WorkflowProcessCodes: string
{
    case NormalFuelRequisition = "2000";
    case OutOfTownFuelRequisition = "2001";
    case OverrideFuelRequisition = "2002";
    case StoresRequisition = "3000";
    //  case StoresReservation = "3000";
    case PurchaseProcess = "4000";

    case WorkOrderClosure = '4001';
}
