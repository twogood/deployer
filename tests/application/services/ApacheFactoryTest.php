<?php

// XXX: can't get autoloading to work :-(

require_once APPLICATION_PATH . '/models/Host.php';
require_once APPLICATION_PATH . '/models/SiteType.php';
require_once APPLICATION_PATH . '/models/Site.php';
require_once APPLICATION_PATH . '/services/Apache.php';
require_once APPLICATION_PATH . '/services/ApacheConfigDirectory.php';
require_once APPLICATION_PATH . '/services/ApacheControl.php';
require_once APPLICATION_PATH . '/services/ApacheFactory.php';

use Application\Model;
use Application\Service;

class ApacheFactoryTest extends PHPUnit_Framework_TestCase
{
	public function testGetDirectoryConfigService()
	{
		$apacheFactory = new Service\ApacheFactory(null);
		$configService = $apacheFactory->getConfigService(
			Model\SiteType::$DIRECTORY);
		$this->assertType('Application\Service\ApacheConfigDirectory', $configService);
	}

	public function testGetControlService()
	{
		$apacheFactory = new Service\ApacheFactory(null);
		$controlService  = $apacheFactory->getControlService(
			'valid-host');
		$this->assertType('Application\Service\ApacheControl', $controlService);
	}

}
	
