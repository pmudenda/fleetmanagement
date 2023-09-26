<?php

namespace App\Exceptions;

use Exception;
use Faker\Provider\Base;
use Throwable;

class WorkflowTaskCreationFailedException extends BaseException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
