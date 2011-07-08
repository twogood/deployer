<?php

// XXX: can't get autoloading to work :-(

require_once APPLICATION_PATH . '/services/DeployDirectory.php';
require_once APPLICATION_PATH . '/services/Repository.php';
require_once APPLICATION_PATH . '/services/Deploy.php';
require_once APPLICATION_PATH . '/services/DeployFactory.php';
require_once APPLICATION_PATH . '/models/Host.php';
require_once APPLICATION_PATH . '/models/SiteType.php';
require_once APPLICATION_PATH . '/models/Site.php';

use Application\Model;
use Application\Service;

class DeployTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidHost()
	{
		$repository = $this->getMockBuilder('Application\Service\Repository')
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
			->will($this->returnValue(new Model\Site()));

		$deployService = new Service\Deploy($repository, null);
		$deployService->deploy('test-site', 'invalid-host');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidSite()
	{
		$repository = $this->getMockBuilder('Application\Service\Repository')
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
			->will($this->returnValue(new Model\Host()));

		$deployService = new Service\Deploy($repository, null);
		$deployService->deploy('invalid-site', 'test-host');
	}

	public function testDeploy()
	{
		$site = new Application\Model\Site();
		$site->name = 'test-site';
		$site->type = Application\Model\SiteType::$DIRECTORY;

		$host = new Application\Model\Host();
		$host->name = 'test-host';

		$repository = $this->getMockBuilder('Application\Service\Repository')
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

		$deployDirectory = $this->getMockBuilder('Application\Service\DeployDirectory')
			->disableOriginalConstructor()
			->getMock();
		$deployDirectory
			->expects($this->once())
			->method('deploy')
			->with($this->equalTo($site), $this->equalTo($host));

		$deployFactory = $this->getMockBuilder('Application\Service\DeployFactory')
			->disableOriginalConstructor()
			->getMock();

		$deployFactory
			->expects($this->once())
			->method('getDeployer')
			->with($this->equalTo($site->type))
			->will($this->returnValue($deployDirectory));

		$deployService = new Service\Deploy($repository, $deployFactory);
		$deployService->deploy('test-site', 'test-host');
	}

}


