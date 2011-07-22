<?php

namespace services\loopia;

class Encoder
{
  private static function encodeRequest($method, $params)
  {
    return xmlrpc_encode_request(
      $method, 
      $params, 
      array('encoding' => 'UTF-8')
    );
  }

  public static function getDomains($username, $password)
  {
    return self::encodeRequest(__FUNCTION__, func_get_args());
  }

  public static function getZoneRecords($username, $password, $customer_number, $domain, $subdomain)
  {
    return self::encodeRequest(__FUNCTION__, func_get_args());
  }

  public static function removeZoneRecord($username, $password, $customer_number, $domain, $subdomain, $record_id)
  {
    return self::encodeRequest(__FUNCTION__, func_get_args());
  }

  public static function addZoneRecord($username, $password, $customer_number, $domain, $subdomain, $record_obj)
  {
    return self::encodeRequest(__FUNCTION__, func_get_args());
  }

}
