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

  private static function endsWith($value, $suffix)
  {
    return substr($value, -strlen($suffix)) == $suffix;
  }

	public function enableSite($siteName, $host)
	{
		list($output, $error) = $this->secureShellService->runCommand(
			$host,
      "/usr/sbin/a2ensite $siteName && sudo /etc/init.d/apache2 reload && echo DEPLOY-SUCCESS || echo DEPLOY-FAILURE");
    if (!self::endsWith($output, 'DEPLOY-SUCCESS'))
      throw new \Exception('Unexpected output: '.$output);
	}
	
}
