<?php

namespace services;

class Apache
{
	private $factory;

	public function __construct($factory = null)
	{
		$this->factory = $factory;
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
}
