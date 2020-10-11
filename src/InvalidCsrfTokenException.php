<?php

namespace Falgun\Middlewares;

use Exception;

final class InvalidCsrfTokenException extends Exception
{

    public function __construct()
    {
        parent::__construct('Invalid CSRF Token provided!', 403);
    }

}
