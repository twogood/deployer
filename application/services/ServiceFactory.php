<?php

namespace services;

class ServiceFactory
{
	private $config;
	private $repository;

	public function __construct($config)
	{
		$this->config = $config;
	}


	public function getRepository()
	{
		if (!$this->repository)
		{
			$repositoryConfig = $this->config['repository'];
			$repositoryPath = $repositoryConfig['path'];
			$this->repository = new Repository($repositoryPath);
		}

		return $this->repository;
	}

    public function getDeployService()
    {
	$secureShellService = new SecureShell();

	$apacheFactory = new ApacheFactory($secureShellService);
	$apacheService = new Apache($apacheFactory);

	$deployFactory = new DeployFactory($apacheService, $secureShellService);
	$deployService = new Deploy($this->getRepository(), $deployFactory);

	return $deployService;
    }

	

}


