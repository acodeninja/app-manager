<?php

namespace AppManager\Deploy;

use AppManager\Deploy\Exceptions\CannotFindDeploymentPlaybookException;
use AppManager\Deploy\Exceptions\CannotFindDeploymentVariableException;
use Symfony\Component\Process\Process;

class Deployment
{
    protected $is_interactive;
    protected $playbook_path;
    protected $targets;
    protected $variables = [];
    protected $log = "";

    /**
     * Deployment constructor.
     * @param string $playbook_path
     * @param array|null $targets
     * @throws CannotFindDeploymentPlaybookException
     */
    public function __construct(string $playbook_path, array $targets = null)
    {
        if (! file_exists($playbook_path)) {
            throw new CannotFindDeploymentPlaybookException;
        }

        $this->is_interactive = php_sapi_name() === 'cli';
        $this->playbook_path = $playbook_path;
        $this->targets = empty($targets) ? ["localhost"] : $targets;
    }

    public function setVariable(string $name, string $value)
    {
        $this->variables[$name] = $value;

        return $this;
    }

    public function getVariable(string $name) : string
    {
        return isset($this->variables[$name]) ? $this->variables[$name] : '';
    }

    public function getRequiredVariables()
    {
        $playbook_contents = file_get_contents($this->playbook_path);
        $playbook_contents = explode("\n", $playbook_contents)[0];
        $playbook_contents = str_replace('## VARIABLES ', '', $playbook_contents);
        $playbook_contents = str_replace(' ##', '', $playbook_contents);
        $playbook_contents = explode(', ', $playbook_contents);
        return $playbook_contents;
    }

    public function getLog() : string
    {
        return $this->log;
    }

    /**
     * @throws CannotFindDeploymentVariableException
     */
    public function canExecute()
    {
        foreach ($this->getRequiredVariables() as $requiredVariable) {
            if (empty($this->getVariable($requiredVariable))) {
                throw new CannotFindDeploymentVariableException;
            }
        }
    }

    /**
     * @throws CannotFindDeploymentVariableException
     */
    public function execute()
    {
        $this->canExecute();

        $commandParts = [
            "/usr/bin/ansible-playbook",
            $this->playbook_path,
            "--inventory " . implode(",", $this->targets).",",
//            $this->is_interactive ? "--ask-become-pass" : "",
            "-vvv"
        ];

        $process = new Process(
            implode(" ", $commandParts),
            null,
            array_merge($this->variables, [
                'ANSIBLE_HOST_KEY_CHECKING' => 'False',
            ])
        );
        $process->start();

        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                $this->log .= $data;
            } else { // $process::ERR === $type
                $this->log .= $data;
            }
        }
    }
}
