<?php
namespace models;

class Host
{
	public $name;
	public $ssh_fingerprint;
	public $ssh_username;
	public $ssh_pubkeyfile;
	public $ssh_privkeyfile;

	public function __construct($name)
	{
		$this->name = $name;
	}

}
