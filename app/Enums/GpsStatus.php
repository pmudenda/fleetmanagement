<?php

namespace App\Enums;

enum GpsStatus: int {
    const Active = 1;
    const Disabled = 0;
    const Damaged = 2;
}
