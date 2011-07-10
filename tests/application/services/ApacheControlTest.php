<?php

class ApacheControlTest extends PHPUnit_Framework_TestCase
{

	public function testUploadConfiguration()
	{
		$host = new models\Host('test-host');

		$siteConfig = "testtesttest";

		$secureShellService = $this->getMock('services\SecureShell');
		$secureShellService
			->expects($this->once())
			->method('uploadFileData')
			->with($this->equalTo($host), 
				$this->equalTo('/etc/apache2/sites-available/test'),
				$this->equalTo($siteConfig)
				)
			;
		
		$apacheControlService = new services\ApacheControl($secureShellService);
		$apacheControlService->uploadSiteConfig("test", $siteConfig, $host);
	}

	public function testEnableSite()
	{
		$host = new models\Host('test-host');

		$secureShellService = $this->getMock('services\SecureShell');
		$secureShellService
			->expects($this->once())
			->method('runCommand')
			->with($this->equalTo($host), 
				$this->matchesRegularExpression('/a2ensite test.*apache2 reload/')
      )
      ->will($this->returnValue(array('DEPLOY-SUCCESS', '')))
			;

		$apacheControlService = new services\ApacheControl($secureShellService);
		$apacheControlService->enableSite('test-site', $host);
	}
}
