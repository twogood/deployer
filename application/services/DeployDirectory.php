<?php
namespace services;

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
    list($output, $error) = $this->secureShellService->runCommand($host, "git clone -q '$master' '$siteDirectory' && echo DEPLOY-SUCCESS || echo DEPLOY-FAILURE");
    if ($output != 'DEPLOY-SUCCESS')
        throw new \Exception('git clone failed: '.$error);
	}


}

