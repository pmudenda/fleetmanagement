<?php

namespace App\Enums;

enum GatePassType: int {
    case GENERAL = 0;
    case STAND_BY = 1;
    case AUTHORITY_TO_TRAVEL = 2;

    public function label(): string
    {
        // Convert "STAND_BY" → "Stand By"
        return ucwords(strtolower(str_replace('_', ' ', $this->name)));
    }
}
