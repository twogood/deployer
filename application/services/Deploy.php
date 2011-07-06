<?php

class Application_Service_Deploy
{
	private $siteRepository;
	private $hostRepository;
	
	public function __construct($siteRepository, $hostRepository)
	{
		$this->siteRepository = $siteRepository;
		$this->hostRepository = $hostRepository;
	}


	public function deploy($siteName, $hostName)
	{
		$site = $this->siteRepository->getSite($siteName);
		$host = $this->hostRepository->getHost($hostName);

		$deployer = $this->getDeployer($site, $host);
		$deployer->run();
	}

	protected function getDeployer($site, $host)
	{
		switch ($site->type)
		{
			case 'directory':
				return new Application_Service_NormalDeploy($site, $host);
/*
			case 'wordpress':
				break;
			case 'dokuwiki':
				break;
*/
			default:
				throw new Exception('Unknown or missing site type: '. $this->type);
		}
	}

}
