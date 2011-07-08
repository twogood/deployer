<?php

// XXX: can't get autoloading to work :-(

require_once APPLICATION_PATH . '/models/Host.php';
require_once APPLICATION_PATH . '/models/SiteType.php';
require_once APPLICATION_PATH . '/models/Site.php';
require_once APPLICATION_PATH . '/services/Apache.php';
require_once APPLICATION_PATH . '/services/ApacheFactory.php';

use Application\Model;
use Application\Service;

class ApacheTest extends \PHPUnit_Framework_TestCase
{

	public function testDirectoryConfig()
	{
		$site = new Model\Site();
		$site->name = 'test-site';
		$site->type = Model\SiteType::$DIRECTORY;

		$host = new Model\Host();
		$host->name = 'test-host';

		$apacheConfigService = $this->getMock('Application\Service\ApacheConfigDirectory');
		$apacheConfigService
			->expects($this->any())
			->method('createConfig')
			->with($this->equalTo($site->name), $this->equalTo($site->domainNames))
			->will($this->returnValue('mock-config'));

		$apacheControlService = $this
			->getMockBuilder('Application\Service\ApacheControl')
			->disableOriginalConstructor()
			->getMock();

		$apacheControlService
			->expects($this->once())
			->method('uploadSiteConfig')
			->with($this->equalTo($site->name), 'mock-config');

		$apacheControlService
			->expects($this->once())
			->method('enableSite')
			->with($this->equalTo($site->name));


		$apacheFactory = $this->getMockBuilder('Application\Service\ApacheFactory')
			->setConstructorArgs(array(null))
			->getMock();
		$apacheFactory
			->expects($this->once())
			->method('getConfigService')
			->with($this->equalTo($site->type))
			->will($this->returnValue($apacheConfigService))
			;
		$apacheFactory
			->expects($this->once())
			->method('getControlService')
			->with($this->equalTo($host->name))
			->will($this->returnValue($apacheControlService))
			;

		$apacheService = new Service\Apache($apacheFactory);
		$apacheService->deploy($site, $host);
	}

}


