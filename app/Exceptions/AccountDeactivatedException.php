<?php

namespace App\Exceptions;

use Exception;

class AccountDeactivatedException extends Exception
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        $message = 'Your ' . env('APP_NAME') . ' account has been deactivated.';
        
        parent::__construct($message, $code, $previous);
    }
}
