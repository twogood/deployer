<?php

class DeployTest extends \PHPUnit_Framework_TestCase
{
	public function testDeploy()
	{
		$site = new models\Site('test-site');
		$site->type = models\SiteType::$DIRECTORY;

		$host = new models\Host('test-host');

 		$deployDirectory = $this->getMockBuilder('services\DeployDirectory')
			->disableOriginalConstructor()
			->getMock();
		$deployDirectory
			->expects($this->once())
			->method('deploy')
			->with($this->equalTo($site), $this->equalTo($host));

		$deployFactory = $this->getMockBuilder('services\DeployFactory')
			->disableOriginalConstructor()
			->getMock();

		$deployFactory
			->expects($this->once())
			->method('getDeployer')
			->with($this->equalTo($site->type))
			->will($this->returnValue($deployDirectory));

    $deployService = new services\Deploy($deployFactory);
		$deployService->deploy($site, $host);
	}

}


