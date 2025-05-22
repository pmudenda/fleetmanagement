<?php

namespace App\Enums;

enum GatePassStatus: string {

    case NEW ='01';
    case AUTHORIZED = '02';
    case CHECKED = '03';
    case REJECTED = '04';


    public function label(): string
    {
        return ucwords(strtolower(str_replace('_', ' ', $this->name)));
    }

    public function color(): string
    {
        return match($this) {
            self::NEW => 'primary',       // Gray (default for new)
            self::AUTHORIZED => 'warning',  // Yellow
            self::CHECKED => 'success',    // Green
            self::REJECTED => 'danger',    // Red
        };
    }

    public function badge(): string
    {
        return sprintf('<span class="badge badge-lg bg-%s">%s</span>',
            $this->color(),
            $this->label());
    }


}
