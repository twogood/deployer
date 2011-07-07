<?php

// XXX: can't get autoloading to work :-(

require_once APPLICATION_PATH . '/services/DeployDirectory.php';
require_once APPLICATION_PATH . '/services/DeployFactory.php';
require_once APPLICATION_PATH . '/models/SiteType.php';

use Application\Model;
use Application\Service;

class DeployFactoryTest extends PHPUnit_Framework_TestCase
{

	public function testDirectoryType()
	{
		$deployFactory = new Service\DeployFactory(null, null);
		$deployer = $deployFactory->getDeployer(Model\SiteType::$DIRECTORY);
		$this->assertType('Application\Service\DeployDirectory', $deployer);
	}
}
