<?php

namespace App\Domains\Sessions\Exceptions;

use Exception;

class DeviceNotAvailableException extends Exception
{
    protected $message = 'Device is not available to start a session.';
    protected $code = 400;

    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? $this->message);
    }
}
