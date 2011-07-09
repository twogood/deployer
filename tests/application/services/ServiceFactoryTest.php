<?php

class ServiceFactoryTest extends PHPUnit_Framework_TestCase
{
	public function testGetRepository()
	{	
		$config = array('repository' => array( 'path' => 'whatever' ));
		$serviceFactory = new services\ServiceFactory($config);
		$repository = $serviceFactory->getRepository();
		$this->assertInstanceOf('services\Repository', $repository);
	}

	public function testGetDeployService()
	{
		$config = array('repository' => array( 'path' => 'whatever' ));
		$serviceFactory = new services\ServiceFactory($config);
		$deployService = $serviceFactory->getDeployService();
		$this->assertInstanceOf('services\Deploy', $deployService);
	}

}


