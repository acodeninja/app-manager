<?php

namespace AppManager\Git\Exceptions;

use AppManager\Exception;

class CannotFindGitRepositoryException extends Exception
{
    public $message = "Cannot find a git repository at the given location";
}
