<?php

namespace App\Enums;

enum GatePassType: int {
    case GENERAL = 1;
    case STAND_BY = 2;
    case AUTHORITY_TO_TRAVEL = 3;

    public function label(): string
    {
        // Convert "STAND_BY" → "Stand By"
        return ucwords(strtolower(str_replace('_', ' ', $this->name)));
    }
}
