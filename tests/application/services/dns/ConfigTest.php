<?php

class ConfigTest extends PHPUnit_Framework_TestCase
{

  public function testUpdateLocalhost()
  {
    $site = new models\Site('example-site');
    $site->domainNames = array('example.com', 'www.example.com');

    $host = new models\Host('localhost');

    $loopia = $this->getMockBuilder('services\dns\LoopiaImpl')
      ->disableOriginalConstructor()
      ->getMock();
    $loopia
      ->expects($this->at(0))
      ->method('update')
      ->with('example.com', '127.0.0.1');
    $loopia
      ->expects($this->at(1))
      ->method('update')
      ->with('www.example.com', '127.0.0.1');

    $factory = $this->getMockBuilder('services\dns\Factory')
      ->disableOriginalConstructor()
      ->getMock();
    $factory
      ->expects($this->once())
      ->method('getImpl')
      ->with($this->equalTo($site))
      ->will($this->returnValue($loopia));

    $dnsConfig = new services\dns\Config($factory);
    $dnsConfig->update($site, $host);
  }

  public function testUpdateIpAddress()
  {
    $site = new models\Site('example-site');
    $site->domainNames = array('example.com', 'www.example.com');

    $ip = '192.0.43.10';
    $host = new models\Host($ip);

    $loopia = $this->getMockBuilder('services\dns\LoopiaImpl')
      ->disableOriginalConstructor()
      ->getMock();
    $loopia
      ->expects($this->at(0))
      ->method('update')
      ->with('example.com', $ip);
    $loopia
      ->expects($this->at(1))
      ->method('update')
      ->with('www.example.com', $ip);

    $factory = $this->getMockBuilder('services\dns\Factory')
      ->disableOriginalConstructor()
      ->getMock();
    $factory
      ->expects($this->once())
      ->method('getImpl')
      ->with($this->equalTo($site))
      ->will($this->returnValue($loopia));

    $dnsConfig = new services\dns\Config($factory);
    $dnsConfig->update($site, $host);
  }

}
