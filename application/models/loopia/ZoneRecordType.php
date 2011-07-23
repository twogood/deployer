<?php

namespace models\loopia;

class ZoneRecordType
{
  public static $VALID_TYPES = array(
    'A', 
    'AAAA', 
    'CNAME', 
    'MX', 
    'NS',
  );

  private $value;

  public function __construct($type)
  {
    if ($type instanceof ZoneRecordType)
      $this->value = $type->value;
    elseif (!in_array($type, self::$VALID_TYPES))
      throw new \InvalidArgumentException("Invalid type: $type");
    $this->value = $type;
  }

  public function __tostring()
  {
    return $this->value;
  }


}
