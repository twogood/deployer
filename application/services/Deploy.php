<?php
namespace services;

class Deploy
{
	private $deployFactory;
	
	public function __construct($deployFactory)
	{
		$this->deployFactory = $deployFactory;
	}

	public function deploy($site, $host)
  {
 		$deployer = $this->deployFactory->getDeployer($site->type);
		$deployer->deploy($site, $host);
	}
}
