<?php

// XXX: can't get autoloading to work :-(

require_once APPLICATION_PATH . '/services/Deploy.php';
require_once APPLICATION_PATH . '/models/Host.php';
require_once APPLICATION_PATH . '/models/SiteType.php';
require_once APPLICATION_PATH . '/models/Site.php';

class DeployTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidHost()
	{
		$siteRepository = new MockSiteRepository();
		$hostRepository = new MockHostRepository();
		$deployService = new Application_Service_Deploy($siteRepository, $hostRepository);
		$deployService->deploy('test-site', 'invalid-host');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidSite()
	{
		$siteRepository = new MockSiteRepository();
		$hostRepository = new MockHostRepository();
		$deployService = new Application_Service_Deploy($siteRepository, $hostRepository);
		$deployService->deploy('invalid-site', 'test-host');
	}

	public function testDeploy()
	{
		$site = new Application_Model_Site();
		$site->name = 'test-site';
		$site->type = Application_Model_SiteType::$DIRECTORY;
		$siteRepository = new MockSiteRepository();
		$siteRepository->setSite($site);

		$host = new Application_Model_Host();
		$host->name = 'test-host';
		$hostRepository = new MockHostRepository();
		$hostRepository->setHost($host);

		$deployService = new Application_Service_Deploy($siteRepository, $hostRepository);
		$deployService->deploy('test-site', 'test-host');
	}

}


