<?php
namespace services;

class Deploy
{
	private $repository;
	private $apacheService;
	private $secureShellService;
	
	public function __construct($repository, $deployFactory)
	{
		$this->repository = $repository;
		$this->deployFactory = $deployFactory;
	}


	public function deploy($siteName, $hostName)
	{
		$site = $this->repository->getSite($siteName);
		$host = $this->repository->getHost($hostName);

		$deployer = $this->deployFactory->getDeployer($site->type);
		$deployer->deploy($site, $host);
	}


}
