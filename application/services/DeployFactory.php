<?php
namespace services;

class DeployFactory
{
	private $apacheService;
	private $secureShellService;
	
	public function __construct($apacheService, $secureShellService)
	{
		$this->apacheService = $apacheService;
		$this->secureShellService = $secureShellService;
	}

	public function getDeployer($siteType)
	{
		switch ($siteType)
		{
			case 'directory':
				return new DeployDirectory(
					$this->apacheService, $this->secureShellService);
/*
			case 'wordpress':
				break;
			case 'dokuwiki':
				break;
*/
			default:
				throw new \InvalidArgumentException(
					'Unknown or missing site type: '. $siteType);
		}
	}
}

