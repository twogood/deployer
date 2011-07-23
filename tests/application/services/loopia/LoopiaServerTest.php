<?php

class LoopiaServerTest extends PHPUnit_Framework_TestCase
{
  private $username;
  private $password;

  protected function setUp()
  {
    if (getenv('FAST_TESTS'))
    {
      $this->markTestSkipped("LoopiaServerTest is a bit slow");
      return;
    }

    $this->username = getenv('LOOPIA_USERNAME');
    $this->password = getenv('LOOPIA_PASSWORD');

    if (!$this->username || !$this->password)
    {
      $this->markTestSkipped('LOOPIA_USERNAME and LOOPIA_PASSWORD are not set');
    }
  }

  private function getDomains($server)
  {
    $requestXml = services\loopia\Encoder::getDomains(
      $this->username, 
      $this->password);
    $responseXml = $server->xmlrpc($requestXml);

    $response = xmlrpc_decode($responseXml, 'UTF-8');
    $this->assertInternalType('array', $response);

    $item = $response[0];
    $this->assertNotEquals('AUTH_ERROR', $item, 'The account is not authorized to call getDomains');
    $this->assertInternalType('array', $item);
    $this->assertInternalType('string', $item['domain']);

    return $response;
  }

  private function getZoneRecords($server, $domain)
  {
    $requestXml = services\loopia\Encoder::getZoneRecords(
      $this->username, 
      $this->password, 
      '', 
      $domain['domain'], 
      '@' // subdomain must be @ for second-level domain name
    );
    $responseXml = $server->xmlrpc($requestXml);

    $response = xmlrpc_decode($responseXml, 'UTF-8');
    $this->assertInternalType('array', $response);
    $item = $response[0];
    $this->assertInternalType('array', $item);
    $this->assertArrayHasKey('record_id', $item);

    //var_dump($response);
  }

  public function testXmlrpc()
  {
    $server = new services\loopia\Server();

    $domains = $this->getDomains($server);

    $this->getZoneRecords($server, $domains[0]);
   }

}
