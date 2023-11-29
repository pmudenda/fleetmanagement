<?php

namespace App\Exceptions;

use Throwable;

class VehicleOnBoardingException extends BaseException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
