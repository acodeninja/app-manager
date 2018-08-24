<?php

namespace Tests\Unit;

use AppManager\Deploy\Deployment;
use AppManager\Deploy\Exceptions\CannotFindDeploymentPlaybookException;
use AppManager\Deploy\Exceptions\CannotFindDeploymentVariableException;
use AppManager\Deploy\InteractsWithDeployments;
use AppManagerTest\TestCase;


class DeployCodeTest extends TestCase
{
    use InteractsWithDeployments;

    private $playbook;

    public function setUp()
    {
        $this->playbook = __DIR__.'/../files/test.deployment.yml';
    }

    public function tearDown()
    {

    }

    public function testGettingADeployment()
    {
        $this->assertInstanceOf(Deployment::class, $this->getDeployment($this->playbook));
    }

    public function testGettingADeploymentWithAnInvalidPlaybookPathThrowsException()
    {
        $this->expectException(CannotFindDeploymentPlaybookException::class);
        $this->getDeployment('');
    }

    public function testGettingVariablesForADeployment()
    {
        $deployment = $this->getDeployment($this->playbook);
        $variables = $deployment->getRequiredVariables();

        $this->assertContains('TEST_STRING', $variables);
    }

    /**
     * @throws CannotFindDeploymentVariableException
     */
    public function testExecutingADeploymentWithRequiredVariables()
    {
        $deployment = $this->getDeployment($this->playbook);
        $deployment->setVariable('TEST_STRING', 'This is a test!');
        $deployment->execute();

        $this->assertNotEmpty($deployment->getLog());
    }

    /**
     * @throws CannotFindDeploymentVariableException
     */
    public function testExecutingADeploymentWithoutRequiredVariablesThrowsException()
    {
        $this->expectException(CannotFindDeploymentVariableException::class);

        $deployment = $this->getDeployment($this->playbook);
        $deployment->execute();
    }
}
