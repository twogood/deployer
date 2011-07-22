<?php

class LoopiaTest extends PHPUnit_Framework_TestCase
{
  public function testGetZoneRecords()
  {
    $encoder = new services\loopia\Encoder();

    $username = 'user';
    $password = 'pass';
    $customer_number = '';
    $domain = 'example.com';
    $subdomain = '@';

    $requestXml = $encoder->getZoneRecords(
      $username, $password, $customer_number, $domain, $subdomain);

    $responseXml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<methodResponse><params><param><value><array><data><value><struct><member><name>priority</name><value><int>0</int></value></member><member><name>ttl</name><value><int>3600</int></value></member><member><name>record_id</name><value><int>11889407</int></value></member><member><name>rdata</name><value><string>79.99.6.136</string></value></member><member><name>type</name><value><string>A</string></value></member></struct></value><value><struct><member><name>priority</name><value><int>10</int></value></member><member><name>ttl</name><value><int>3600</int></value></member><member><name>record_id</name><value><int>11889435</int></value></member><member><name>rdata</name><value><string>aspmx.l.google.com.</string></value></member><member><name>type</name><value><string>MX</string></value></member></struct></value><value><struct><member><name>priority</name><value><int>20</int></value></member><member><name>ttl</name><value><int>3600</int></value></member><member><name>record_id</name><value><int>11889441</int></value></member><member><name>rdata</name><value><string>alt1.aspmx.l.google.com.</string></value></member><member><name>type</name><value><string>MX</string></value></member></struct></value><value><struct><member><name>priority</name><value><int>20</int></value></member><member><name>ttl</name><value><int>3600</int></value></member><member><name>record_id</name><value><int>11889442</int></value></member><member><name>rdata</name><value><string>alt2.aspmx.l.google.com.</string></value></member><member><name>type</name><value><string>MX</string></value></member></struct></value><value><struct><member><name>priority</name><value><int>30</int></value></member><member><name>ttl</name><value><int>3600</int></value></member><member><name>record_id</name><value><int>11889456</int></value></member><member><name>rdata</name><value><string>aspmx2.googlemail.com.</string></value></member><member><name>type</name><value><string>MX</string></value></member></struct></value><value><struct><member><name>priority</name><value><int>30</int></value></member><member><name>ttl</name><value><int>3600</int></value></member><member><name>record_id</name><value><int>11889457</int></value></member><member><name>rdata</name><value><string>aspmx3.googlemail.com.</string></value></member><member><name>type</name><value><string>MX</string></value></member></struct></value><value><struct><member><name>priority</name><value><int>30</int></value></member><member><name>ttl</name><value><int>3600</int></value></member><member><name>record_id</name><value><int>11889458</int></value></member><member><name>rdata</name><value><string>aspmx4.googlemail.com.</string></value></member><member><name>type</name><value><string>MX</string></value></member></struct></value><value><struct><member><name>priority</name><value><int>30</int></value></member><member><name>ttl</name><value><int>3600</int></value></member><member><name>record_id</name><value><int>11889459</int></value></member><member><name>rdata</name><value><string>aspmx5.googlemail.com.</string></value></member><member><name>type</name><value><string>MX</string></value></member></struct></value><value><struct><member><name>priority</name><value><int>0</int></value></member><member><name>ttl</name><value><int>3600</int></value></member><member><name>record_id</name><value><int>11889317</int></value></member><member><name>rdata</name><value><string>ns1.loopia.se.</string></value></member><member><name>type</name><value><string>NS</string></value></member></struct></value><value><struct><member><name>priority</name><value><int>0</int></value></member><member><name>ttl</name><value><int>3600</int></value></member><member><name>record_id</name><value><int>11889318</int></value></member><member><name>rdata</name><value><string>ns2.loopia.se.</string></value></member><member><name>type</name><value><string>NS</string></value></member></struct></value></data></array></value></param></params></methodResponse>
EOF;

    $server = $this->getMock('services\loopia\Server');
    $server
      ->expects($this->once())
      ->method('xmlrpc')
      ->with($requestXml)
      ->will($this->returnValue($responseXml));

    $config = array(
      'username' => $username,
      'password' => $password,
    );
    $loopia = new services\loopia\Loopia($server, $config);

    $zoneRecords = $loopia->getZoneRecords($domain, null);
    $this->assertInternalType('array', $zoneRecords);
    $zoneRecord = $zoneRecords[0];
    $this->assertInstanceOf('models\loopia\ZoneRecord', $zoneRecord);
    $this->assertInstanceOf('models\loopia\ZoneRecordType', $zoneRecord->type);

    //var_export($zoneRecords);
  }

  /**
   * @expectedException  Exception
   */
  public function testGetZoneRecordsError()
  {
    $encoder = new services\loopia\Encoder();

    $username = 'user';
    $password = 'pass';
    $domain = 'example.com';
    $subdomain = '@';

    $responseXml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<methodResponse>
  <params>
    <param>
      <value>
        <string>AUTH_ERROR</string>
      </value>
    </param>
  </params>
</methodResponse>
EOF;

    $server = $this->getMock('services\loopia\Server');
    $server
      ->expects($this->once())
      ->method('xmlrpc')
      ->will($this->returnValue($responseXml));

    $config = array(
      'username' => $username,
      'password' => $password,
    );
    $loopia = new services\loopia\Loopia($server, $config);

    $loopia->getZoneRecords($domain, $subdomain);
  }

  public function testRemoveZoneRecord()
  {
    $encoder = new services\loopia\Encoder();

    $username = 'user';
    $password = 'pass';
    $customer_number = '';
    $domain = 'example.com';
    $subdomain = 'www';
    $record_id = 42;

    $requestXml = $encoder->removeZoneRecord(
      $username, $password, $customer_number, $domain, $subdomain, $record_id);

    $responseXml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<methodResponse>
  <params>
    <param>
      <value>
        <string>OK</string>
      </value>
    </param>
  </params>
</methodResponse>
EOF;

    $server = $this->getMock('services\loopia\Server');
    $server
      ->expects($this->once())
      ->method('xmlrpc')
      ->with($requestXml)
      ->will($this->returnValue($responseXml));

    $config = array(
      'username' => $username,
      'password' => $password,
    );
    $loopia = new services\loopia\Loopia($server, $config);

    $result = $loopia->removeZoneRecord($domain, $subdomain, $record_id);
    $this->assertEquals('OK', (string)$result);
  }

  public function testAddZoneRecord()
  {
    $encoder = new services\loopia\Encoder();

    $username = 'user';
    $password = 'pass';
    $customer_number = '';
    $domain = 'example.com';
    $subdomain = 'www';
    $priority = 0;
    $type = 'CNAME';
    $ttl = 3600;
    $rdata = 'example.com';
    $record = (object)array(
      'priority' => $priority,
      'type' => $type,
      'ttl' => $ttl,
      'record_id' => 0,
      'rdata' => $rdata,
      );
    $requestXml = $encoder->addZoneRecord(
      $username, $password, $customer_number, $domain, $subdomain, $record);

    $responseXml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<methodResponse>
  <params>
    <param>
      <value>
        <string>OK</string>
      </value>
    </param>
  </params>
</methodResponse>
EOF;

    $server = $this->getMock('services\loopia\Server');
    $server
      ->expects($this->once())
      ->method('xmlrpc')
      ->with($requestXml)
      ->will($this->returnValue($responseXml));

    $config = array(
      'username' => $username,
      'password' => $password,
    );
    $loopia = new services\loopia\Loopia($server, $config);

    $result = $loopia->addZoneRecord($domain, $subdomain, 
      new \models\loopia\ZoneRecord(new \models\loopia\ZoneRecordType($type), $rdata));

    $this->assertEquals('OK', (string)$result);
  }

}
