<?php

namespace AppManager\Git;


use AppManager\Git\Exceptions\CannotCheckoutGitBranchException;
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
     * @param string $name
     * @return array|mixed|null
     */
    public function __get(string $name)
    {
        switch ($name) {
            case "branches":
                return $this->cleanText(
                    $this->workingCopy->getBranches()->getIterator()->getArrayCopy()
                );
                break;
            case "branch":
                return str_replace(
                    "* ",
                    "",
                    $this->cleanText($this->workingCopy->branch())
                );
                break;
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );
        return null;
    }

    /**
     * @param $branch
     * @throws CannotCheckoutGitBranchException
     */
    public function checkout($branch)
    {
        if (empty($branch) || false === in_array($branch, $this->branches)) {
            throw new CannotCheckoutGitBranchException;
        }
    }

    /**
     * @param array|string $subject
     * @return array|mixed
     */
    private function cleanText($subject)
    {
        if (is_array($subject)) {
            foreach ($subject as $key => $item) {
                $subject[$key] = $this->cleanText($item);
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
