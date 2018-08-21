<?php

namespace AppManagerTest;

use AppManagerTest\Assertions\IsGitRepository;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

class TestCase extends PHPUnit_TestCase
{
    /**
     * @param string $repository_location
     */
    public function assertDirectoryIsGitRepo(string $repository_location)
    {
        self::assertThat($repository_location, new IsGitRepository);
    }

    /**
     * @return null|string
     */
    public function getRandomTempDir()
    {
        $directory = sys_get_temp_dir()."/".uniqid();

        return mkdir($directory) ? $directory : null;
    }
}
