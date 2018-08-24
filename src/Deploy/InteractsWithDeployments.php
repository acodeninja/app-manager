<?php

namespace AppManager\Deploy;

trait InteractsWithDeployments
{
    public function getDeployment(string $playbook_path, array $targets = null) : Deployment
    {
        return new Deployment($playbook_path, $targets);
    }
}
