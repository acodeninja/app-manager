<?php

namespace Tests\Unit;

use AppManager\Git\Exceptions\CannotCloneGitRepositoryException;
use AppManager\Git\Exceptions\CannotFindGitRepositoryException;
use AppManager\Git\Exceptions\CannotInitializeGitRepositoryException;
use AppManager\Git\InteractsWithGitRepository;
use AppManager\Git\Repository;
use AppManagerTest\TestCase;
use PHPUnit\Framework\Error\Notice;

class GitRepositoryTest extends TestCase
{
    use InteractsWithGitRepository;

    private $git_repo_location;

    public function setUp()
    {
        $this->git_repo_location = $this->getRandomTempDir();
    }

    public function tearDown()
    {
        ! file_exists($this->git_repo_location) || rmdir_r($this->git_repo_location);
    }


    /**
     * @throws CannotInitializeGitRepositoryException
     */
    public function testInitializingARepository()
    {
        $repository = $this->initializeRepository($this->git_repo_location);

        $this->assertDirectoryIsGitRepo($this->git_repo_location);
        $this->assertInstanceOf(Repository::class, $repository);
    }

    /**
     * @throws CannotInitializeGitRepositoryException
     */
    public function testThrowsExceptionWhenInitializingWithNoLocation()
    {
        $this->expectException(CannotInitializeGitRepositoryException::class);
        $this->initializeRepository("");
    }

    /**
     * @throws CannotInitializeGitRepositoryException
     */
    public function testThrowsExceptionWhenInitializingAnExistingRepository()
    {
        $this->expectException(CannotInitializeGitRepositoryException::class);
        $this->initializeRepository($this->git_repo_location);
        $this->initializeRepository($this->git_repo_location);
    }

    /**
     * @throws CannotCloneGitRepositoryException
     */
    public function testCloningARepository()
    {
        $repository = $this->cloneRepository(
            getenv("TEST_GIT_REPO"),
            $this->git_repo_location
        );

        $this->assertDirectoryIsGitRepo($this->git_repo_location);
        $this->assertInstanceOf(Repository::class, $repository);
    }

    /**
     * @throws CannotCloneGitRepositoryException
     */
    public function testThrowsExceptionWhenCloningWithNoRemote()
    {
        $this->expectException(CannotCloneGitRepositoryException::class);
        $this->cloneRepository(
            "",
            $this->git_repo_location
        );
    }

    /**
     * @throws CannotCloneGitRepositoryException
     */
    public function testThrowsExceptionWhenCloningWithNoLocation()
    {
        $this->expectException(CannotCloneGitRepositoryException::class);
        $this->cloneRepository(
            getenv("TEST_GIT_REPO"),
            ""
        );
    }

    /**
     * @throws CannotInitializeGitRepositoryException
     * @throws CannotFindGitRepositoryException
     */
    public function testGetsAKnownRepository()
    {
        $this->initializeRepository($this->git_repo_location);

        $repository = $this->getRepository($this->git_repo_location);
        $this->assertInstanceOf(Repository::class, $repository);
    }

    /**
     * @throws CannotFindGitRepositoryException
     */
    public function testThrowsExceptionWhenGettingRepositoryForEmptyString()
    {
        $this->expectException(CannotFindGitRepositoryException::class);
        $this->getRepository($this->git_repo_location);
    }

    /**
     * @throws CannotFindGitRepositoryException
     */
    public function testThrowsExceptionWhenGettingRepositoryForEmptyFolder()
    {
        $this->expectException(CannotFindGitRepositoryException::class);
        $this->getRepository($this->git_repo_location);
    }

    /**
     * @throws CannotCloneGitRepositoryException
     */
    public function testGetBranch()
    {
        $repository = $this->cloneRepository(
            getenv("TEST_GIT_REPO"),
            $this->git_repo_location
        );

        $this->assertEquals("master", $repository->getBranch());
    }

    /**
     * @throws CannotCloneGitRepositoryException
     */
    public function testGetBranches()
    {
        $repository = $this->cloneRepository(
            getenv("TEST_GIT_REPO"),
            $this->git_repo_location
        );

        $this->assertContains("master", $repository->getBranches());
    }
}
