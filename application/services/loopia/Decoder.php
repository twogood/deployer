<?php

namespace services\loopia;

class Decoder
{

  private static function decodeResponse($xml)
  {
    return xmlrpc_decode($xml, 'UTF-8');
  }

  private static function convertZoneRecord($array)
  {
    return \models\loopia\ZoneRecord::__set_state($array);
  }

  public static function decodeZoneRecords($xml)
  {
    $response = self::decodeResponse($xml);

    if (!is_array($response))
    {
      throw new \Exception((string)$response);
    }

    $records = $response;

    $zoneRecords = array();
    foreach ($records as $record)
    {
      $zoneRecords[] = self::convertZoneRecord($record);
    }

    return $zoneRecords;
  }

  public static function decodeStatus($xml)
  {
    $status = self::decodeResponse($xml);
    return $status;
  }

}
