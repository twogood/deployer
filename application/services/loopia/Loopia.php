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
      $subdomain); 

    $responseXml = $this->server->xmlrpc($request);
    return Decoder::decodeZoneRecords($responseXml);
  }
}

