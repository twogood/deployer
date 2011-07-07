<?php

namespace Application\Service;

class Apache
{
	private $factory;

	public function __construct($factory = null)
	{
		if ($factory)
			$this->factory = $factory;
		else
			$this->factory = new ApacheFactory();
	}	

	public function deploy($site, $host)
	{
		$configService = $this->factory->getConfigService($site->type);
		$controlService = $this->factory->getControlService($host->name);

		$siteConfig = $configService->createConfig(
			$site->name, $site->domainNames, $site->extraVirtualHostConfig);

		$controlService->uploadSiteConfig($site->name, $siteConfig, $host);
		$controlService->enableSite($site->name, $host);
	}


	public function createConfig($site)
	{
		$siteConfig = 
		$this->controlService->uploadSiteConfig($siteName, $siteConfig);
		$this->controlService->enableSite($siteName);
	}

}
