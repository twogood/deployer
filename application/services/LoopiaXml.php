<?php

namespace services;

class LoopiaXml
{
  private static $VALID_NAMES = array('getZoneRecords');

  public function __call ( $name , $arguments )
  {
    if (!in_array($name, self::$VALID_NAMES))
      throw new Exception('No such method in LoopiaXml class: '.$name);

    return xmlrpc_encode_request(
      $name, 
      $arguments, 
      array('encoding' => 'UTF-8')
    );
  }

}
