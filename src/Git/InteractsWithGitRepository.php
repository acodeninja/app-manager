<?php

namespace AppManager\Git;

use AppManager\Git\Exceptions\CannotCloneGitRepositoryException;
use AppManager\Git\Exceptions\CannotFindGitRepositoryException;
use AppManager\Git\Exceptions\CannotInitializeGitRepositoryException;
use GitWrapper\GitWorkingCopy;
use GitWrapper\GitWrapper;

/**
 * Trait InteractsWithGitRepository
 * @package AppManager\Git
 */
trait InteractsWithGitRepository
{
    /**
     * @param string $remote
     * @param string $location
     * @return Repository
     * @throws CannotCloneGitRepositoryException
     */
    public function cloneRepository(string $remote, string $location) : Repository
    {
        if (empty($remote) || empty($location)) {
            throw new CannotCloneGitRepositoryException;
        }

        return new Repository(
            (new GitWrapper)
                ->cloneRepository($remote, $location)
        );
    }

    /**
     * @param string $location
     * @return Repository
     * @throws CannotInitializeGitRepositoryException
     */
    public function initializeRepository(string $location) : Repository
    {
        if (empty($location)) {
            throw new CannotInitializeGitRepositoryException;
        }

        return new Repository(
            (new GitWrapper)
                ->init($location)
        );
    }

    /**
     * @param string $location
     * @return Repository
     * @throws CannotFindGitRepositoryException
     */
    public function getRepository(string $location) : Repository
    {
        if (empty($location) || ! file_exists($location.'/.git')) {
            throw new CannotFindGitRepositoryException;
        }

        return new Repository(new GitWorkingCopy(new GitWrapper, $location));
    }
}
