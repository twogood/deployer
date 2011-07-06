<?php


class Application_Model_SiteType
{
	private static $DIRECTORY;
	public static $VALID_SITE_TYPES = array('directory');

	private $value;

	// Called from global scope below!
	public static function init()
	{
		self::$DIRECTORY = new Application_Model_SiteType('directory');
	}

	public function __construct($value)
	{
		if (!in_array($value, self::$VALID_SITE_TYPES))
			throw new InvalidArgumentException("Invalid site type: $value");
		$this->value = $value;
	}

	public function value()
	{
		return $this->value;
	}
}

Application_Model_SiteType::init();


