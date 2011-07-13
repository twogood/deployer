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

  public function testXmlrpc()
  {
    $server = new services\loopia\Server();

    $requestXml = services\loopia\Encoder::getDomains($this->username, $this->password);
    $responseXml = $server->xmlrpc($requestXml);

    $response = xmlrpc_decode($responseXml, 'UTF-8');
    $this->assertInternalType('array', $response);

    $item = $response[0];
    $this->assertNotEquals('AUTH_ERROR', $item, 'The account is not authorized to call getDomains');
    $this->assertInternalType('array', $item);
    $this->assertInternalType('string', $item['domain']);

    //var_dump($response);
  }

}
