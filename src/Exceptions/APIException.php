<?php

namespace Sdkconsultoria\Core\Exceptions;

use Exception;

class APIException extends Exception
{
    public function __construct(array $errors = [], $code = 0)
    {
        parent::__construct(json_encode($errors), $code);
    }
}
