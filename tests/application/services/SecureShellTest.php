<?php

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
		$host = new models\Host('invalid-host');
		
		$secureShellService = new services\SecureShell();
		$secureShellService->runCommand($host, "whoami");	
	}

	/**
	 * @expectedException Exception
	 */
	public function testRunCommandLocalhostInvalidFingerprint()
	{
		$host = new models\Host('localhost');
		$host->ssh_fingerprint = 'invalid';
		
		$secureShellService = new services\SecureShell();
		$secureShellService->runCommand($host, "whoami");	
	}

	private function allowPublicKey($pubkeyfile)
	{
		file_put_contents(
			$this->authorized_keys, 
			file_get_contents($pubkeyfile),
			FILE_APPEND);
	}

	private function createHost($sshUsername)
	{
		$host = new models\Host('localhost');
		$host->ssh_fingerprint = 'invalid';
		$host->ssh_username = $sshUsername;
		$host->ssh_pubkeyfile = getcwd().'/id_dsa.pub';
		$host->ssh_privkeyfile = getcwd().'/id_dsa';

		$this->assertTrue(file_exists($host->ssh_pubkeyfile));
		$this->assertTrue(file_exists($host->ssh_privkeyfile));

		$ssh = ssh2_connect($host->name);
		$host->ssh_fingerprint = ssh2_fingerprint($ssh);
		unset($ssh);

		return $host;
  }

	/**
   * @expectedException Exception
   * @expectedExceptionMessage Authentication failed for deploy using public key
	 */
  public function testDefaultUsername()
  {
 		$host = $this->createHost(null);
		
		$secureShellService = new services\SecureShell();
    $result = $secureShellService->runCommand($host, "pwd");
    $this->fail("Not supposed to get result '$result'");
	}

	public function testRunCommandLocalhost()
	{
		$host = $this->createHost(exec('whoami'));
		$this->allowPublicKey($host->ssh_pubkeyfile);
		
		$secureShellService = new services\SecureShell();
		list($result, $error) = $secureShellService->runCommand($host, "/usr/bin/whoami && (echo error message >&2) && echo hepp");
		$this->assertEquals($host->ssh_username . "\nhepp", $result);
	}

	public function testUploadFileData()
	{
		$host = $this->createHost(exec('whoami'));
		$this->allowPublicKey($host->ssh_pubkeyfile);

		$fileName = tempnam('/tmp', 'SecureShellTest');
		chmod($fileName, 0666);
		$expectedFileData = "whatever" . mt_srand();
		
		$secureShellService = new services\SecureShell();
		$secureShellService->uploadFileData($host, $fileName, $expectedFileData);

		$actualFileData = file_get_contents($fileName);
		$this->assertEquals($expectedFileData, $actualFileData);

		unlink($fileName);
	}

}

