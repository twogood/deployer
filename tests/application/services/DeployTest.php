<?php

class DeployTest extends \PHPUnit_Framework_TestCase
{
	public function testDeploy()
	{
		$site = new models\Site('test-site');
		$site->type = models\SiteType::$DIRECTORY;

		$host = new models\Host('test-host');

    /*
		$repository = $this->getMockBuilder('services\Repository')
			->disableOriginalConstructor()
			->getMock();
		$repository
			->expects($this->once())
			->method('getHost')
			->with($this->equalTo('test-host'))
			->will($this->returnValue($host));

		$repository
			->expects($this->once())
			->method('getSite')
			->with($this->equalTo('test-site'))
      ->will($this->returnValue($site));
     */

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

    $deployService = new services\Deploy(/*$repository,*/ $deployFactory);
		$deployService->deploy($site, $host);
	}

}


