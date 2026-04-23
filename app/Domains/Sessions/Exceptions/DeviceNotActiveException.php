<?php

namespace App\Domains\Sessions\Exceptions;

use Exception;

class DeviceNotActiveException extends Exception
{
    protected $message = 'Device must be IN_USE to stop a session.';
    protected $code = 400;
}
