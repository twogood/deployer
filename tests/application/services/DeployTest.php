<?php

class DeployTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidHost()
	{
		$repository = $this->getMockBuilder('services\Repository')
			->disableOriginalConstructor()
			->getMock();
		$repository
			->expects($this->once())
			->method('getHost')
			->with($this->equalTo('invalid-host'))
			->will($this->throwException(new InvalidArgumentException()));

		$repository
			->expects($this->any())
			->method('getSite')
			->with($this->equalTo('test-site'))
			->will($this->returnValue(new models\Site('test-site')));

		$deployService = new services\Deploy($repository, null);
		$deployService->deploy('test-site', 'invalid-host');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidSite()
	{
		$repository = $this->getMockBuilder('services\Repository')
			->disableOriginalConstructor()
			->getMock();
		$repository
			->expects($this->once())
			->method('getSite')
			->with($this->equalTo('invalid-site'))
			->will($this->throwException(new InvalidArgumentException()));

/*
1) DeployTest::testInvalidSite
Expectation failed for method name is equal to <string:getHost> when invoked zero or more times.
Mocked method does not exist.
https://github.com/sebastianbergmann/phpunit-mock-objects/issues/46
https://github.com/sebastianbergmann/phpunit/issues/269
*/
		$repository
			->expects($this->any())
			->method('getHost')
//			->with($this->equalTo('test-host'))
			->will($this->returnValue(new models\Host('test-host')));

		$deployService = new services\Deploy($repository, null);
		$deployService->deploy('invalid-site', 'test-host');
	}

	public function testDeploy()
	{
		$site = new models\Site('test-site');
		$site->type = models\SiteType::$DIRECTORY;

		$host = new models\Host('test-host');

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

		$deployService = new services\Deploy($repository, $deployFactory);
		$deployService->deploy('test-site', 'test-host');
	}

}


