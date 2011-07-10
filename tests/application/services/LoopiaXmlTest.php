<?php

class LoopiaXmlTest extends PHPUnit_Framework_TestCase
{

  /**
   * @expectedException Exception
   */
  public function testInvalidMethod()
  {
    $loopiaXml = new services\LoopiaXml();
    $loopiaXml->thisRequestDoesNotExist(42);
  }

  public function testGetZoneRecords()
  {
    

    $loopiaXml = new services\LoopiaXml();

    $username = 'user';
    $password = 'pass';
    $customer_number = '';
    $domain = 'sanktanna.nu';
    $subdomain = '@';
    $actualXml = $loopiaXml->getZoneRecords(
      $username, $password, $customer_number, $domain, $subdomain);

    $expectedXml = <<<EOF
<?xml version="1.0"?>
<methodCall>
  <methodName>getZoneRecords</methodName>
  <params>
    <param><value><string>$username</string></value></param>
    <param><value><string>$password</string></value></param>
    <param><value><string></string></value></param>
    <param><value><string>$domain</string></value></param>
    <param><value><string>$subdomain</string></value></param>
  </params>
</methodCall>
EOF;

    $this->assertXmlStringEqualsXmlString($expectedXml, $actualXml);

  }

}
