<?php

class SecureShellMock
{
	private $lastCommand;
	private $lastFileName;

	public function shell($command)
	{
		$this->lastCommand = $command;
	}

	public function uploadFileData($fileName, $fileData)
	{
		$this->lastFileName = $fileName;
		$this->lastFileData = $fileData;
	}

	public function getLastCommand()
	{
		return $this->lastCommand;
	}

	public function getLastFileName()
	{
		return $this->lastFileName;
	}

	public function getLastFileData()
	{
		return $this->lastFileData;
	}

}
