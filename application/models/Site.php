<?php
namespace models;

class Site
{
	public $name;
	public $domainNames = array();
	public $type; // Application_Model_SiteType
	public $extraVirtualHostConfig;
	public $master;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function getDirectory()
	{
		return '/var/www/' . $this->name;
	}
}
