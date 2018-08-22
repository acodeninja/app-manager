<?php

namespace AppManager\Git\Exceptions;

use AppManager\Exception;

class CannotCheckoutGitBranchIllegalCharacterException extends Exception
{
    public $message = "Cannot checkout the given branch for this repository, it contains an illegal character.";
}
