<?php

class FactoryTest extends PHPUnit_Framework_TestCase
{

  public function testGetLoopiaImpl()
  {
    $site = new models\Site('example-site');
    $site->dnsProvider = 'Loopia';

    $config = array(
      'loopia' => array(
        'username' => '',
        'password' => '',
      )
    );

    $factory = new services\dns\Factory($config);
    $impl = $factory->getImpl($site);

    $this->assertInstanceOf('services\dns\LoopiaImpl', $impl);
  }

  public function testNullProvider()
  {
    $site = new models\Site('example-site');
    $site->dnsProvider = null;

    $factory = new services\dns\Factory(array());
    $impl = $factory->getImpl($site);

    $this->assertNull($impl);
  }

  public function testEmptyProvider()
  {
    $site = new models\Site('example-site');
    $site->dnsProvider = '';

    $factory = new services\dns\Factory(array());
    $impl = $factory->getImpl($site);

    $this->assertNull($impl);
  }

  /**
   * @expectedException Exception
   */
  public function testInvalidProvider()
  {
    $site = new models\Site('example-site');
    $site->dnsProvider = 'invalid-provider';

    $factory = new services\dns\Factory(array());
    $impl = $factory->getImpl($site);
  }


}

