<?php

namespace App\Enums;

enum WorkflowProcessCodes: string
{
    case LocalFuelRequisition = "2000";
    case OutOfTownFuelRequisition = "2001";
    case OverrideFuelRequisition = "2002";
    case StoresRequisition = "3000";
    //  case StoresReservation = "3000";
    case PurchaseProcess = "4000";

    case WorkOrderClosure = '4001';
    case WorkOrderOpened = '4002';
}
