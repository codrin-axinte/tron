<?php

namespace App\Exceptions;

use Exception;

class TronNumericFaultException extends Exception
{
    protected $message = '[NUMERIC_FAULT] Transfer has failed.';
}
