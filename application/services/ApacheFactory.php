<?php
namespace Application\Service;

use Application\Model;

class ApacheFactory
{
	private $secureShellService;

	public function __construct($secureShellService)
	{
		$this->secureShellService = $secureShellService;
	}

	public function getConfigService(Model\SiteType $siteType)
	{
		return new ApacheConfigDirectory();
	}

	public function getControlService($hostName)
	{
		return new ApacheControl($this->secureShellService);
	}
}
