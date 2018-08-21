<?php

namespace AppManagerTest\Assertions;

use PHPUnit\Framework\Constraint\Constraint;

class IsGitRepository extends Constraint
{
    public function toString(): string
    {
        return 'directory is a git repository';
    }

    protected function matches($other): bool
    {
        return \is_dir($other."/.git");
    }

    protected function failureDescription($other): string
    {
        return \sprintf(
            'directory "%s" is a git repository',
            $other
        );
    }
}
