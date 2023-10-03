<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class UserSimulationException extends BaseException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
