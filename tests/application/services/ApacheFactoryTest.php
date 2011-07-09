<?php

class ApacheFactoryTest extends PHPUnit_Framework_TestCase
{
	public function testGetDirectoryConfigService()
	{
		$apacheFactory = new services\ApacheFactory(null);
		$configService = $apacheFactory->getConfigService(
			models\SiteType::$DIRECTORY);
		$this->assertInstanceOf('services\ApacheConfigDirectory', $configService);
	}

	public function testGetControlService()
	{
		$apacheFactory = new services\ApacheFactory(null);
		$controlService  = $apacheFactory->getControlService(
			'valid-host');
		$this->assertInstanceOf('services\ApacheControl', $controlService);
	}

}
	
