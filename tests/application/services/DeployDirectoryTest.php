<?php


// XXX: can't get autoloading to work :-(

require_once APPLICATION_PATH . '/services/SecureShell.php';
require_once APPLICATION_PATH . '/services/DeployDirectory.php';
require_once APPLICATION_PATH . '/models/Host.php';
require_once APPLICATION_PATH . '/models/SiteType.php';
require_once APPLICATION_PATH . '/models/Site.php';

use Application\Model;
use Application\Service;

class DeployDirectoryTest extends PHPUnit_Framework_TestCase
{

	public function testDeployDirectory()
	{
		$site = new Model\Site();
		$site->name = 'test-site';
		$site->type = Model\SiteType::$DIRECTORY;
		$site->master = 'ssh://user@host/dir';

		$host = new Model\Host();
		$host->name = 'test-host';

		$apacheService = $this->getMockBuilder('Application\Service\Apache')
			->disableOriginalConstructor()
			->getMock();
		$apacheService
			->expects($this->once())
			->method('deploy')
			->with($this->equalTo($site), $this->equalTo($host))
			;

		$secureShellService = $this->getMock('Application\Service\SecureShell');
		$secureShellService
			->expects($this->once())
			->method('shell')
			->with($this->equalTo($host), 
				$this->equalTo("git clone 'ssh://user@host/dir' '/var/www/test-site'")
				)
			;

		$deployService = new Service\DeployDirectory($apacheService, $secureShellService);
		$deployService->deploy($site, $host);
	}

}


