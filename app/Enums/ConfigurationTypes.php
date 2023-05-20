<?php

namespace App\Enums;

enum ConfigurationTypes : string
{
    case ACCIDENT_TYPES = "accidenttypes";
    case INSURANCE_TYPE = "insurancetype";
    case INSURANCE_COMPANY = "insurancecompany";
    case ACCIDENT_NATURE = "accidentnature";
    case BUSINESS_AREAS = "businessareas";
    case VEHICLE_STATUS = "vehiclestatus";
    case FUEL_LEVELS = "FuelLevel";
    case STATUS_GENERAL = "statusgeneral";
    case STORES_MOVEMENT_TYPES = "movementtype";
    case INSURANCE_SUB_TYPES = "insurancesubtypes";
    case WORKS_FLOW_SECTION = "wkshp_section";
}
