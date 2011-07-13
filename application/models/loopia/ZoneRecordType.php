<?php

namespace models\loopia;

class ZoneRecordType
{
  public static $VALID_TYPES = array(
    'A', 
    'CNAME', 
    'MX', 
    'NS',
  );

  private $value;

  public function __construct($value)
  {
    if (!in_array($value, self::$VALID_TYPES))
      throw new InvalidArgumentException("Invalid type: $value");
    $this->value = $value;
  }

  public function __tostring()
  {
    return $this->value;
  }


}
