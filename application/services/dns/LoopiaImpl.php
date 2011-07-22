<?php

namespace services\dns;

class LoopiaImpl
{
  private $loopia;

  public function __construct($loopia)
  {
    $this->loopia = $loopia;
  }

  public function update($domainName, $ip)
  {
    $parts = explode('.', $domainName);

    $firstDomain = array_pop($parts);
    $secondDomain = array_pop($parts);

    $domain = $secondDomain . '.' . $firstDomain;

    $subdomain = implode('.', $parts);

    $zoneRecords = $this->loopia->getZoneRecords($domain, $subdomain);

    foreach ($zoneRecords as $zoneRecord)
    {
      switch ((string)$zoneRecord->type)
      {
      case 'A':
      case 'CNAME':
        $this->loopia->removeZoneRecord($domain, $subdomain, $zoneRecord->record_id);
        break;
      }
    }

    $newRecord = new \models\loopia\ZoneRecord(new \models\loopia\ZoneRecordType('A'), $ip); 

    $this->loopia->addZoneRecord($domain, $subdomain, $newRecord);

  }

}

