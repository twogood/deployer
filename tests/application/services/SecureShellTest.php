<?php

// XXX: can't get autoloading to work :-(

require_once APPLICATION_PATH . '/models/Host.php';
require_once APPLICATION_PATH . '/services/SecureShell.php';

use Application\Model;
use Application\Service;

class SecureShellTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$home = getenv('HOME');
		$this->authorized_keys = $home . '/.ssh/authorized_keys';
		$this->authorized_keys_backup = $home . '/.ssh/authorized_keys.SecureShellTest';

		copy($this->authorized_keys, $this->authorized_keys_backup);
	}

	public function tearDown()
	{
		if (file_exists($this->authorized_keys_backup))
		{
			copy($this->authorized_keys_backup, $this->authorized_keys);
			unlink($this->authorized_keys_backup);
			chmod($this->authorized_keys, 0600);
		}
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testRunCommandInvalidHost()
	{
		$host = new Model\Host();
		$host->name = 'invalid-host';
		
		$secureShellService = new Service\SecureShell();
		$secureShellService->runCommand($host, "whoami");	
	}

	/**
	 * @expectedException Exception
	 */
	public function testRunCommandLocalhostInvalidFingerprint()
	{
		$host = new Model\Host();
		$host->name = 'localhost';
		$host->fingerprint = 'invalid';
		
		$secureShellService = new Service\SecureShell();
		$secureShellService->runCommand($host, "whoami");	
	}

	private function allowPublicKey($pubkeyfile)
	{
		file_put_contents(
			$this->authorized_keys, 
			file_get_contents($pubkeyfile),
			FILE_APPEND);
	}

	private function createHost()
	{
		$host = new Model\Host();
		$host->name = 'localhost';
		$host->fingerprint = 'invalid';
		$host->ssh_username = exec('whoami');
		$host->ssh_pubkeyfile = getcwd().'/id_dsa.pub';
		$host->ssh_privkeyfile = getcwd().'/id_dsa';

		$this->assertTrue(file_exists($host->ssh_pubkeyfile));
		$this->assertTrue(file_exists($host->ssh_privkeyfile));

		$ssh = ssh2_connect($host->name);
		$host->fingerprint = ssh2_fingerprint($ssh);
		unset($ssh);

		return $host;
	}

	public function testRunCommandLocalhost()
	{
		$host = $this->createHost();
		$this->allowPublicKey($host->ssh_pubkeyfile);
		
		$secureShellService = new Service\SecureShell();
		$result = $secureShellService->runCommand($host, "/usr/bin/whoami && echo hepp");
		$this->assertEquals($host->ssh_username . "\nhepp", $result);
	}

	public function testUploadFileData()
	{
		$host = $this->createHost();
		$this->allowPublicKey($host->ssh_pubkeyfile);

		$fileName = tempnam('/tmp', 'SecureShellTest');
		chmod($fileName, 0666);
		$expectedFileData = "whatever" . mt_srand();
		
		$secureShellService = new Service\SecureShell();
		$secureShellService->uploadFileData($host, $fileName, $expectedFileData);

		$actualFileData = file_get_contents($fileName);
		$this->assertEquals($expectedFileData, $actualFileData);

		unlink($fileName);
	}

}

