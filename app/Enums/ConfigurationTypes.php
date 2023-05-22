<?php

namespace App\Enums;

enum ConfigurationTypes : string
{
    case ACCIDENT_TYPES = "ACCID_TYPE";
    case INSURANCE_TYPE = "INSUR_TYP";
    case INSURANCE_COMPANY = "INSUR_COM";
    case ACCIDENT_NATURE = "ACCID_NAT";
    case VEHICLE_STATUS = "STAT_VEH";
    case FUEL_LEVELS = "FUEL_LEVEL";
    case STATUS_GENERAL = "STAT_GEN";
    case STORES_MOVEMENT_TYPES = "MOVE_TYP";
    case INSURANCE_SUB_TYPES = "INSUR_SUB_TYPE";
    case WORK_SHOP_SECTION = "WORK_SHOP_SEC";
    const LICENSE_CLASS = 'LICE_CLS';
}
