<?php
namespace Application\Model;

class SiteType
{
	public static $DIRECTORY;
	public static $VALID_SITE_TYPES = array('directory');

	private $value;

	// @codeCoverageIgnoreStart
	public static function init()
	{
		self::$DIRECTORY = new self('directory');
	}
	// @codeCoverageIgnoreEnd

	public function __construct($value)
	{
		if (!in_array($value, self::$VALID_SITE_TYPES))
			throw new InvalidArgumentException("Invalid site type: $value");
		$this->value = $value;
	}

	public function __tostring()
	{
		return $this->value;
	}
}

/*
class SiteTypeDirectory extends SiteType
{ 
	public function __construct()
	{
		parent('directory');
	}
}
*/

SiteType::init();


