<?php

class ServiceFactoryTest extends PHPUnit_Framework_TestCase
{
	public function testCreateDeployService()
	{
		$config = array('repository' => 
			array(
				'path' => 'whatever'

			)
			);
		$serviceFactory = new services\ServiceFactory($config);
		$deployService = $serviceFactory->createDeployService();
		$this->assertType('services\Deploy', $deployService);
	}

}


