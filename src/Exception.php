<?php

namespace AppManager;

use Throwable;

class Exception extends \Exception
{
    public function __construct(string $message = null, int $code = null, Throwable $previous = null)
    {
        if (property_exists($this, 'message')) {
            $message = $this->message;
        }

        parent::__construct($message, $code, $previous);
    }
}
