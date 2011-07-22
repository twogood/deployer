<?php

class LoopiaXmlTest extends PHPUnit_Framework_TestCase
{
  public function testGetZoneRecords()
  {
    $encoder = new services\loopia\Encoder();

    $username = 'user';
    $password = 'pass';
    $customer_number = '';
    $domain = 'example.com';
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


  public function testAddZoneRecord()
  {
    $encoder = new services\loopia\Encoder();

    $username = 'user';
    $password = 'pass';
    $customer_number = '';
    $domain = 'example.com';
    $subdomain = '@';
    $priority = 0;
    $type = 'CNAME';
    $ttl = 3600;
    $rdata = 'example.com';
    $record = (object)array(
      'priority' => $priority,
      'type' => $type,
      'ttl' => $ttl,
      'rdata' => $rdata,
      );
    $actualXml = $encoder->addZoneRecord(
      $username, $password, $customer_number, $domain, $subdomain, $record);

    $expectedXml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<methodCall>
  <methodName>addZoneRecord</methodName>
  <params>
    <param><value><string>$username</string></value></param>
    <param><value><string>$password</string></value></param>
    <param><value><string>$customer_number</string></value></param>
    <param><value><string>$domain</string></value></param>
    <param><value><string>$subdomain</string></value></param>
    <param><value><struct>
      <member>
        <name>priority</name>
        <value><int>$priority</int></value>
      </member>
      <member>
        <name>type</name>
        <value><string>$type</string></value>
      </member>
      <member>
        <name>ttl</name>
        <value><int>$ttl</int></value>
      </member>
      <member>
        <name>rdata</name>
        <value><string>$rdata</string></value>
      </member>
    </struct></value></param>
  </params>
</methodCall>
EOF;

    $this->assertXmlStringEqualsXmlString($expectedXml, $actualXml);

  }

}
