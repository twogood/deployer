<?php

class ApacheTest extends \PHPUnit_Framework_TestCase
{

	public function testDirectoryConfig()
	{
		$site = new models\Site('test-site');
		$site->type = models\SiteType::$DIRECTORY;

		$host = new models\Host('test-host');

		$apacheConfigService = $this->getMock('services\ApacheConfigDirectory');
		$apacheConfigService
			->expects($this->any())
			->method('createConfig')
			->with($this->equalTo($site->name), $this->equalTo($site->domainNames))
			->will($this->returnValue('mock-config'));

		$apacheControlService = $this
			->getMockBuilder('services\ApacheControl')
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


		$apacheFactory = $this->getMockBuilder('services\ApacheFactory')
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

		$apacheService = new services\Apache($apacheFactory);
		$apacheService->deploy($site, $host);
	}

}


