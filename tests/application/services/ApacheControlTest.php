<?php

// XXX: can't get autoloading to work :-(

require_once APPLICATION_PATH . '/services/ApacheControl.php';

class ApacheControlTest extends PHPUnit_Framework_TestCase
{

	public function testUploadConfiguration()
	{
		$secureShellService = new SecureShellMock();

		$siteConfig = "testtesttest";
		
		$apacheControlService = new Application_Service_ApacheControl($secureShellService);
		$apacheControlService->uploadSiteConfig("test", $siteConfig);

		$lastFileName = $secureShellService->getLastFileName();
		$this->assertEquals('/etc/apache2/sites-available/test', $lastFileName);

		$lastFileData = $secureShellService->getLastFileData();
		$this->assertEquals($siteConfig, $lastFileData);
	}

	public function testEnableSite()
	{
		$secureShellService = new SecureShellMock();

		$apacheControlService = new Application_Service_ApacheControl($secureShellService);
		$apacheControlService->enableSite("test");

		$lastCommand = $secureShellService->getLastCommand();

		$this->assertRegExp('/a2ensite test.*&&.*apache2 reload/', $lastCommand);
	}
}
