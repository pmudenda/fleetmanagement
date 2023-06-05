<?php

namespace App\Enums;

enum Modules: string
{
    case FuelRequisition = "FUEL_REQ";
    case SparesRequisition = "SPARES_REQ";
    case PurchaseRequisition = "PUR";
    case StoresRequisition = 'STR';
    case Requisition = 'REQ';
    const FuelReq = 'FR';

    const JOB_CARD = 'JOB_CAR';
    const Material = 'MAT';
}
