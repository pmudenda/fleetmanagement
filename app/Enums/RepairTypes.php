<?php

namespace App\Enums;

enum RepairTypes: string
{
    case AccidentRepair = "001";
    case GeneralRepair = "002";
    case GeneralService = "003";
    case ContractedService = "004";
}
