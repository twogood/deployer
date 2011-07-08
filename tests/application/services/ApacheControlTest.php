<?php

// XXX: can't get autoloading to work :-(

require_once APPLICATION_PATH . '/services/ApacheControl.php';
require_once APPLICATION_PATH . '/models/Host.php';

use Application\Model;
use Application\Service;

class ApacheControlTest extends PHPUnit_Framework_TestCase
{

	public function testUploadConfiguration()
	{
		$host = new Model\Host();
		$host->name = 'test-host';

		$siteConfig = "testtesttest";

		$secureShellService = $this->getMock('Application\Service\SecureShell');
		$secureShellService
			->expects($this->once())
			->method('uploadFileData')
			->with($this->equalTo($host), 
				$this->equalTo('/etc/apache2/sites-available/test'),
				$this->equalTo($siteConfig)
				)
			;
		
		$apacheControlService = new Service\ApacheControl($secureShellService);
		$apacheControlService->uploadSiteConfig("test", $siteConfig, $host);
	}

	public function testEnableSite()
	{
		$host = new Model\Host();
		$host->name = 'test-host';

		$secureShellService = $this->getMock('Application\Service\SecureShell');
		$secureShellService
			->expects($this->once())
			->method('runCommand')
			->with($this->equalTo($host), 
				$this->matchesRegularExpression('/a2ensite test.*apache2 reload/')
				)
			;

		$apacheControlService = new Service\ApacheControl($secureShellService);
		$apacheControlService->enableSite('test-site', $host);
	}
}
