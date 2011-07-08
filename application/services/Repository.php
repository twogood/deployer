<?php
namespace Application\Service;

use Application\Model;

class Repository
{
	private $repositoryPath;

	public function __construct($repositoryPath)
	{
		$this->repositoryPath = $repositoryPath;
	}

	protected function readIniFile($directory, $name)
	{
		$filename = $this->repositoryPath . '/'. $directory. '/' . $name;
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
		$array = $this->readIniFile('sites', $siteName);

		$result = new Model\Site($siteName);
		foreach ($array as $key => $value)
		{
			switch ($key)
			{
				case 'name':	// ignore
					break;
				case 'type':
					$result->$key = new Model\SiteType($value);
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
		throw new \InvalidArgumentException();
	}

}
