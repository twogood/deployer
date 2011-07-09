<?php

class DeployFactoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidType()
	{
		$deployFactory = new services\DeployFactory(null, null);
		$deployer = $deployFactory->getDeployer(null);
		$this->assertInstanceOf('services\DeployDirectory', $deployer);
	}

	public function testDirectoryType()
	{
		$deployFactory = new services\DeployFactory(null, null);
		$deployer = $deployFactory->getDeployer(models\SiteType::$DIRECTORY);
		$this->assertInstanceOf('services\DeployDirectory', $deployer);
	}
}
