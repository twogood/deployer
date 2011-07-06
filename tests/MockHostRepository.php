<?php 

class MockHostRepository
{
	private $host;

	public function getHost($hostName)
	{
		if (!isset($this->host) || $hostName != $this->host->name)
			throw new InvalidArgumentException("Unknown host");
		return $this->host;
	}

	public function setHost($host)
	{
		$this->host = $host;
	}

	

}
