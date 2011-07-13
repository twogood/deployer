<?php

class LoopiaXmlTest extends PHPUnit_Framework_TestCase
{
  public function testGetZoneRecords()
  {
    $encoder = new services\loopia\Encoder();

    $username = 'user';
    $password = 'pass';
    $customer_number = '';
    $domain = 'sanktanna.nu';
    $subdomain = '@';
    $actualXml = $encoder->getZoneRecords(
      $username, $password, $customer_number, $domain, $subdomain);

    $expectedXml = <<<EOF
<?xml version="1.0"?>
<methodCall>
  <methodName>getZoneRecords</methodName>
  <params>
    <param><value><string>$username</string></value></param>
    <param><value><string>$password</string></value></param>
    <param><value><string>$customer_number</string></value></param>
    <param><value><string>$domain</string></value></param>
    <param><value><string>$subdomain</string></value></param>
  </params>
</methodCall>
EOF;

    $this->assertXmlStringEqualsXmlString($expectedXml, $actualXml);

  }

}
