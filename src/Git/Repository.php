<?php

namespace AppManager\Git;


use AppManager\Git\Exceptions\CannotCheckoutGitBranchException;
use AppManager\Git\Exceptions\CannotCheckoutGitBranchIllegalCharacterException;
use GitWrapper\GitException;
use GitWrapper\GitWorkingCopy;

/**
 * Class Repository
 * @property array branches
 * @property string branch
 * @package AppManager\Git
 */
class Repository
{
    /**
     * @var GitWorkingCopy
     */
    private $workingCopy;

    /**
     * Repository constructor.
     * @param GitWorkingCopy $workingCopy
     */
    public function __construct(GitWorkingCopy $workingCopy)
    {
        $this->workingCopy = $workingCopy;
    }

    /**
     * @return array
     */
    public function getBranches(): array
    {
        return $this->cleanBranchText(
            $this->workingCopy
                ->getBranches()
                ->getIterator()
                ->getArrayCopy()
        );
    }

    /**
     * @return string
     */
    public function getBranch(): string
    {
        return $this->cleanBranchText(
            $this->workingCopy
                ->getWrapper()
                ->git(
                    "rev-parse --abbrev-ref HEAD",
                    $this->workingCopy->getDirectory()
                )
        );
    }

    /**
     * @param string $branch
     * @param bool $new
     * @throws CannotCheckoutGitBranchException
     * @throws CannotCheckoutGitBranchIllegalCharacterException
     */
    public function checkout(string $branch, bool $new = false)
    {
        if (empty($branch) || (! in_array($branch, $this->getBranches()) && ! $new)) {
            throw new CannotCheckoutGitBranchException;
        }

        try {
            $new ?
                $this->workingCopy->checkoutNewBranch($branch) :
                $this->workingCopy->checkout($branch);
        } catch (GitException $gitException) {
            throw new CannotCheckoutGitBranchIllegalCharacterException;
        }
    }

    /**
     * @param array|string $subject
     * @return array|mixed
     */
    private function cleanBranchText($subject)
    {
        if (is_array($subject)) {
            foreach ($subject as $key => $item) {
                $subject[$key] = $this->cleanBranchText($item);
            }

            return $subject;
        }

        return str_replace(
            "\n",
            "",
            str_replace(
                "\\n",
                "",
                preg_replace(
                    '#\\x1b[[][^A-Za-z]*[A-Za-z]#',
                    '',
                    $subject
                )
            )
        );
    }
}
