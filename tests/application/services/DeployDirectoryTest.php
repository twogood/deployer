<?php

class DeployDirectoryTest extends PHPUnit_Framework_TestCase
{

	public function testDeployDirectory()
	{
		$site = new models\Site('test-site');
		$site->type = models\SiteType::$DIRECTORY;
		$site->master = 'ssh://user@host/dir';

		$host = new models\Host('test-host');

		$apacheService = $this->getMockBuilder('services\Apache')
			->disableOriginalConstructor()
			->getMock();
		$apacheService
			->expects($this->once())
			->method('deploy')
			->with($this->equalTo($site), $this->equalTo($host))
			;

		$secureShellService = $this->getMock('services\SecureShell');
		$secureShellService
			->expects($this->once())
			->method('runCommand')
			->with($this->equalTo($host), 
				$this->equalTo("git clone -q 'ssh://user@host/dir' '/var/www/test-site' && echo DEPLOY-SUCCESS || echo DEPLOY-FAILURE")
				)
      ->will($this->returnValue(array('DEPLOY-SUCCESS', '')))
			;

		$deployService = new services\DeployDirectory($apacheService, $secureShellService);
		$deployService->deploy($site, $host);
	}

}


