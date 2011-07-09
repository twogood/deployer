<?php

namespace services;

class ServiceFactory
{
	private $config;

	public function __construct($config)
	{
		$this->config = $config;
	}


    public function createDeployService()
    {
	$repositoryConfig = $this->config['repository'];
	
	$repositoryPath = $repositoryConfig['path'];
	$repository = new Repository($repositoryPath);

	$secureShellService = new SecureShell();

	$apacheFactory = new ApacheFactory($secureShellService);
	$apacheService = new Apache($apacheFactory);

	$deployFactory = new DeployFactory($apacheService, $secureShellService);
	$deployService = new Deploy($repository, $deployFactory);

	return $deployService;
    }

	

}


