<?php

namespace Tests\Unit;

use AppManager\Git\Exceptions\CannotCheckoutGitBranchException;
use AppManager\Git\Exceptions\CannotCheckoutGitBranchIllegalCharacterException;
use AppManager\Git\Exceptions\CannotCloneGitRepositoryException;
use AppManager\Git\InteractsWithGitRepository;
use AppManager\Git\Repository;
use AppManagerTest\TestCase;

class GitBranchingTest extends TestCase
{
    use InteractsWithGitRepository;

    /**
     * @var string
     */
    private $git_repo_location;

    /**
     * @var Repository
     */
    private $git_repo;

    /**
     * @throws CannotCloneGitRepositoryException
     */
    public function setUp()
    {
        $this->git_repo_location = $this->getRandomTempDir();
        $this->git_repo = $this->cloneRepository(
            getenv("TEST_GIT_REPO"),
            $this->git_repo_location
        );
    }

    public function tearDown()
    {
        ! file_exists($this->git_repo_location) || rmdir_r($this->git_repo_location);
    }

    /**
     * @throws CannotCheckoutGitBranchException
     * @throws CannotCheckoutGitBranchIllegalCharacterException
     */
    public function testCanCheckoutAnExistingBranch()
    {
        $this->git_repo->checkout("master");
        $this->assertEquals("master", $this->git_repo->getBranch());
    }

    /**
     * @throws CannotCheckoutGitBranchException
     * @throws CannotCheckoutGitBranchIllegalCharacterException
     */
    public function testThrowsExceptionWhenCheckingOutEmptyBranch()
    {
        $this->expectException(CannotCheckoutGitBranchException::class);
        $this->git_repo->checkout("");
    }

    /**
     * @throws CannotCheckoutGitBranchException
     * @throws CannotCheckoutGitBranchIllegalCharacterException
     */
    public function testThrowsExceptionWhenCheckingOutANonExistentBranch()
    {
        $this->expectException(CannotCheckoutGitBranchException::class);
        $this->git_repo->checkout("non-existent-branch");
    }

    /**
     * @throws CannotCheckoutGitBranchException
     * @throws CannotCheckoutGitBranchIllegalCharacterException
     */
    public function testCanCreateANewBranch()
    {
        $this->git_repo->checkout("new-branch", true);
        $this->assertEquals("new-branch", $this->git_repo->getBranch());
    }

    /**
     * @throws CannotCheckoutGitBranchException
     * @throws CannotCheckoutGitBranchIllegalCharacterException
     */
    public function testThrowsExceptionWhenCheckingOutWithIllegalBranchName()
    {
        $this->expectException(CannotCheckoutGitBranchIllegalCharacterException::class);
        $this->git_repo->checkout("illegal~branch", true);
    }
}
