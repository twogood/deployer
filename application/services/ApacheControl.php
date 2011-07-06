<?php

class Application_Service_ApacheControl
{
	private $secureShellService;

	public function __construct($secureShellService)
	{
		$this->secureShellService = $secureShellService;
	}

	public function uploadSiteConfig($siteName, $siteConfig)
	{
		$this->secureShellService->
			uploadFileData("/etc/apache2/sites-available/$siteName", $siteConfig);
	}

	public function enableSite($siteName)
	{
		$this->secureShellService->shell(
			"/usr/sbin/a2ensite $siteName && sudo /etc/init.d/apache2 reload");
	}
	
}
