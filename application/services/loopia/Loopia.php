<?php

namespace services\loopia;

class Loopia
{
  private $server;
  private $encoder;
  private $username;
  private $password;

  public function __construct(Server $server, $config)
  {
    $this->server = $server;
    $this->encoder = new Encoder();

    $this->username = $config['username'];
    $this->password = $config['password'];
  }

  public function getZoneRecords($domain, $subdomain)
  {
    $request = $this->encoder->getZoneRecords(
      $this->username,
      $this->password,
      '',
      $domain, 
      empty($subdomain) ? '@' : $subdomain); 

    $responseXml = $this->server->xmlrpc($request);
    return Decoder::decodeZoneRecords($responseXml);
  }

  public function removeZoneRecord($domain, $subdomain, $record_id)
  {
    $request = $this->encoder->removeZoneRecord(
      $this->username,
      $this->password,
      '',
      $domain, 
      empty($subdomain) ? '@' : $subdomain,
      $record_id); 

    $responseXml = $this->server->xmlrpc($request);
    return Decoder::decodeStatus($responseXml);
  }

  public function addZoneRecord($domain, $subdomain, \models\loopia\ZoneRecord $zoneRecord)
  {
    $record = (array)$zoneRecord;
    $record['type'] = (string)$record['type'];
    $record_obj = (object)$record;

    $request = $this->encoder->addZoneRecord(
      $this->username,
      $this->password,
      '',
      $domain, 
      empty($subdomain) ? '@' : $subdomain,
      $record_obj); 

    $responseXml = $this->server->xmlrpc($request);
    return Decoder::decodeStatus($responseXml);
  }

}

