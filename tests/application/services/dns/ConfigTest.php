<?php

class ConfigTest extends PHPUnit_Framework_TestCase
{

  public function testUpdate()
  {
    $site = new models\Site('example-site');
    $site->domainNames = array('example.com', 'www.example.com');

    $host = new models\Host('example-host');

    $loopia = $this->getMock('services\dns\LoopiaImpl');
    $loopia
      ->expects($this->once())
      ->method('update')
      ->with($this->equalTo($site), $this->equalTo($host));

    $factory = $this->getMock('services\dns\Factory');
    $factory
      ->expects($this->once())
      ->method('getImpl')
      ->with($this->equalTo($site))
      ->will($this->returnValue($loopia));

    $dnsConfig = new services\dns\Config($factory);
    $dnsConfig->update($site, $host);
  }

}
