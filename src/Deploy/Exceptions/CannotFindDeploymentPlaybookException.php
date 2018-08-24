<?php

namespace AppManager\Deploy\Exceptions;

use AppManager\Exception;

class CannotFindDeploymentPlaybookException extends Exception
{
    public $message = "Cannot find the playbook for this deployment";
}
