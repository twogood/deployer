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
    $result = new \models\loopia\ZoneRecord();

		foreach ($array as $key => $value)
		{
			switch ($key)
			{
				case 'type':
					$result->$key = new \models\loopia\ZoneRecordType($value);
					break;
				default:
					$result->$key = $value;
					break;
			}
    }  

    return $result;
  }

  public static function decodeZoneRecords($xml)
  {
    $records = self::decodeResponse($xml);

    $zoneRecords = array();
    foreach ($records as $record)
    {
      $zoneRecords[] = self::convertZoneRecord($record);
    }

    return $zoneRecords;
  }

}
