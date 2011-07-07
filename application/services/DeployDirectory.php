<?php
namespace Application\Service;

class DeployDirectory
{
	private $site;
	private $host;
	private $apacheService;
	private $secureShellService;

	public function __construct($apacheService, $secureShellService)
	{
		$this->apacheService = $apacheService;
		$this->secureShellService = $secureShellService;
	}

	public function deploy($site, $host)
	{
		$this->apacheService->deploy($site, $host);

		$master = $site->master;
		$siteDirectory = $site->getDirectory();
		$this->secureShellService->shell($host, "git clone '$master' '$siteDirectory'");
	}


}

