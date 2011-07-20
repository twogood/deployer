<?php

class LoopiaImplTest extends PHPUnit_Framework_TestCase
{

  public function testUpdate()
  {
    $site = new models\Site('example-site');
    $site->domainNames = array('example.com', 'www.example.com');

    $host = new models\Host('example-host');

    $loopiaImpl = new services\dns\LoopiaImpl();
    $loopiaImpl->update($site, $host);

  }

}


