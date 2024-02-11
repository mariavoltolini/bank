<?php

namespace App\Exceptions;

use Exception;

class BusinessException extends Exception
{
    public function __construct($message = "", $code = 422, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
