<?php

namespace AppManager\Git\Exceptions;

use AppManager\Exception;

class CannotInitializeGitRepositoryException extends Exception
{
    public $message = "Cannot initialize the given git repository";
}
