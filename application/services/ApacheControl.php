<?php
namespace services;

class ApacheControl
{
	private $secureShellService;

	public function __construct($secureShellService)
	{
		$this->secureShellService = $secureShellService;
	}

	public function uploadSiteConfig($siteName, $siteConfig, $host)
	{
		$this->secureShellService->
			uploadFileData($host, 
			"/etc/apache2/sites-available/$siteName", $siteConfig);
	}

	public function enableSite($siteName, $host)
	{
		$this->secureShellService->runCommand(
			$host,
			"/usr/sbin/a2ensite $siteName && sudo /etc/init.d/apache2 reload");
	}
	
}
