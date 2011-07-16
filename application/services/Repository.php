<?php
namespace services;

class Repository
{
	private $repositoryPath;

	public function __construct($config)
	{
		$this->repositoryPath = $config['path'];
	}

	protected function readIniFile($pathComponents)
	{
		$filename = $this->repositoryPath . '/' . implode('/', $pathComponents);
		$ini = @file_get_contents($filename);
		if ($ini === false)
		{
			throw new \InvalidArgumentException("Could not read file: " . $filename);
		}

		$array = @parse_ini_string($ini);
		if ($array === false)
		{
			throw new \InvalidArgumentException(
				"Failed to parse file: " . $filename);
		}
		return $array;
	}

	public function getSite($siteName)
	{
		$array = $this->readIniFile(array('sites', $siteName));

		$result = new \models\Site($siteName);
		foreach ($array as $key => $value)
		{
			switch ($key)
			{
				case 'name':	// ignore
					break;
				case 'type':
					$result->$key = new \models\SiteType($value);
					break;
				default:
					$result->$key = $value;
					break;
			}
		}

		return $result;
	}	

	public function getHost($hostName)
	{
		$array = $this->readIniFile(array('hosts', $hostName, 'config'));
		$result = new \models\Host($hostName);
		foreach ($array as $key => $value)
		{
			switch ($key)
			{
				case 'name':	// ignore
					break;
				default:
					$result->$key = $value;
					break;
			}
		}

		return $result;
	}

	private function listDirectory($subDirectory)
	{
		$result = scandir($this->repositoryPath . '/' . $subDirectory);

		return array_diff($result, array('.', '..'));
	}

	public function getSiteNames()
	{
		return self::listDirectory('sites');
	}

	public function getHostNames()
	{
		return self::listDirectory('hosts');
	}

}
