<?php

class FactoryTest extends PHPUnit_Framework_TestCase
{

  public function testGetImpl()
  {
    $site = new models\Site('example-site');
    $site->domainNames = array('example.com', 'www.example.com');

    $factory = new services\dns\Factory();
    $impl = $factory->getImpl($site);

    $this->assertInstanceOf('services\dns\LoopiaImpl', $impl);
  }
}

