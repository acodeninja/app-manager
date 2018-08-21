<?php

namespace AppManager\Git\Exceptions;

use AppManager\Exception;

class CannotCloneGitRepositoryException extends Exception
{
    public $message = "Cannot clone the given git repository";
}
