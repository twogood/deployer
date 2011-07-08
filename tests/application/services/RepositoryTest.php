<?php

require_once 'vfsStream/vfsStream.php';

// XXX: can't get autoloading to work :-(

require_once APPLICATION_PATH . '/services/Repository.php';
require_once APPLICATION_PATH . '/models/Host.php';
require_once APPLICATION_PATH . '/models/Site.php';
require_once APPLICATION_PATH . '/models/SiteType.php';

use Application\Model;
use Application\Service;

class RepositoryTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		vfsStreamWrapper::register();
		vfsStreamWrapper::setRoot(new vfsStreamDirectory('root'));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidHost()
	{
		$repository = new Service\Repository(vfsStream::url('/invalid-path'));
		$repository->getHost('invalid-host');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidSite()
	{
		$repository = new Service\Repository(vfsStream::url('/invalid-path'));
		$repository->getSite('invalid-site');
	}

	public function testValidSite()
	{
		$repositoryPath = vfsStream::url('root');
		$sitesPath = $repositoryPath . '/sites';
		$this->assertTrue(mkdir($sitesPath, 0777, true));
		$iniData = <<<EOF
name = ignore-me
domainNames[] = test1
domainNames[] = test2
master = ssh://user@host/path/to/master
type = directory
EOF;

		$siteFilePath = $sitesPath . '/valid-site';
		$result = file_put_contents($siteFilePath, $iniData);
		$this->assertNotEquals(false, $result);

		$repository = new Service\Repository($repositoryPath);
		$site = $repository->getSite('valid-site');
		$this->assertType('Application\Model\Site', $site);

		$this->assertEquals('valid-site', $site->name);
		$this->assertEquals('test1', $site->domainNames[0]);
		$this->assertEquals('test2', $site->domainNames[1]);
		$this->assertType('Application\Model\SiteType', $site->type);
		$this->assertEquals(Model\SiteType::$DIRECTORY, $site->type);
	}
}

