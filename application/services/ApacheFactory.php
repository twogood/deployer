<?php
namespace services;

use Application\Model;

class ApacheFactory
{
	private $secureShellService;

	public function __construct($secureShellService)
	{
		$this->secureShellService = $secureShellService;
	}

	public function getConfigService(\models\SiteType $siteType)
	{
		return new ApacheConfigDirectory();
	}

	public function getControlService($hostName)
	{
		return new ApacheControl($this->secureShellService);
	}
}
