<?php

namespace App\Enums;

enum Modules: string
{
    case FuelRequisition = "FUEL_REQ";
    case SparesRequisition = "SPARES_REQ";
    case PurchaseRequisition = "PUR";
    case StoresRequisition = 'STR';

    case Requisition = 'REQ';
    case FUEL_REQUISITION = 'FR';
    case WORKSHOP_DOCUMENT = 'WAC';
    case JOB_CARD = 'JOB_CAR';
    case MATERIAL = 'MAT';

    const VEHICLE = "VEH";
}
