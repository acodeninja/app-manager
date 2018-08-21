<?php

namespace AppManager\Git\Exceptions;

use AppManager\Exception;

class CannotCheckoutGitBranchException extends Exception
{
    public $message = "Cannot checkout the given branch for this repository";
}
