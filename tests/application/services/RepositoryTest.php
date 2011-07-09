<?php

require_once 'vfsStream/vfsStream.php';

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
		$repository = new services\Repository(vfsStream::url('/invalid-path'));
		$repository->getHost('invalid-host');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidSite()
	{
		$repository = new services\Repository(vfsStream::url('/invalid-path'));
		$repository->getSite('invalid-site');
	}

	/**

	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidSiteFile()
	{
		$repositoryPath = vfsStream::url('root');
		$sitesPath = $repositoryPath . '/sites';
		$this->assertTrue(mkdir($sitesPath, 0777, true));
		$iniData = '?{}|&~![()^ = invalid';

		$siteFilePath = $sitesPath . '/valid-site';
		$result = file_put_contents($siteFilePath, $iniData);
		$this->assertNotEquals(false, $result);

		$repository = new services\Repository($repositoryPath);
		$repository->getSite('valid-site');
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

		$repository = new services\Repository($repositoryPath);
		$site = $repository->getSite('valid-site');
		$this->assertType('models\Site', $site);

		$this->assertEquals('valid-site', $site->name);
		$this->assertEquals('test1', $site->domainNames[0]);
		$this->assertEquals('test2', $site->domainNames[1]);
		$this->assertType('models\SiteType', $site->type);
		$this->assertEquals(models\SiteType::$DIRECTORY, $site->type);
	}


	public function testValidHost()
	{
		$repositoryPath = vfsStream::url('root');
		$hostDir = $repositoryPath . '/hosts/valid-host';
		$this->assertTrue(mkdir($hostDir, 0777, true));
		$iniData = <<<EOF
name = ignore-me
key = value
EOF;

		$hostFilePath = $hostDir . '/config';
		$result = file_put_contents($hostFilePath, $iniData);
		$this->assertNotEquals(false, $result);

		$repository = new services\Repository($repositoryPath);
		$host = $repository->getHost('valid-host');
		$this->assertType('models\Host', $host);
		$this->assertEquals('valid-host', $host->name);

	}


}

