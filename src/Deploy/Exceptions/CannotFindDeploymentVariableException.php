<?php

namespace AppManager\Deploy\Exceptions;

use AppManager\Exception;

class CannotFindDeploymentVariableException extends Exception
{
    public $message = "Not all required variables are set on this deployment";
}
