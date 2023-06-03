<?php

namespace App\Enums;

enum RepairTypes: string
{
    case GeneralRepair = "002";
    case GeneralService = "003";
    case AccidentRepair = "001";
}
