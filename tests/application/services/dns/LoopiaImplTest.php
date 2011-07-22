<?php

class LoopiaImplTest extends PHPUnit_Framework_TestCase
{

  private static function getExampleComZoneRecords()
  {
    return array (
      0 => 
      models\loopia\ZoneRecord::__set_state(array(
        'priority' => 0,
        'ttl' => 3600,
        'record_id' => 11889407,
        'rdata' => '79.99.6.136',
        'type' => new models\loopia\ZoneRecordType('A')
      )),
      1 => 
      models\loopia\ZoneRecord::__set_state(array(
        'priority' => 10,
        'ttl' => 3600,
        'record_id' => 11889435,
        'rdata' => 'aspmx.l.google.com.',
        'type' => new models\loopia\ZoneRecordType('MX')
      )),
      2 => 
      models\loopia\ZoneRecord::__set_state(array(
        'priority' => 20,
        'ttl' => 3600,
        'record_id' => 11889441,
        'rdata' => 'alt1.aspmx.l.google.com.',
        'type' => new models\loopia\ZoneRecordType('MX')
      )),
      3 => 
      models\loopia\ZoneRecord::__set_state(array(
        'priority' => 20,
        'ttl' => 3600,
        'record_id' => 11889442,
        'rdata' => 'alt2.aspmx.l.google.com.',
        'type' => new models\loopia\ZoneRecordType('MX')
      )),
      4 => 
      models\loopia\ZoneRecord::__set_state(array(
        'priority' => 30,
        'ttl' => 3600,
        'record_id' => 11889456,
        'rdata' => 'aspmx2.googlemail.com.',
        'type' => new models\loopia\ZoneRecordType('MX')
      )),
      5 => 
      models\loopia\ZoneRecord::__set_state(array(
        'priority' => 30,
        'ttl' => 3600,
        'record_id' => 11889457,
        'rdata' => 'aspmx3.googlemail.com.',
        'type' => new models\loopia\ZoneRecordType('MX')
      )),
      6 => 
      models\loopia\ZoneRecord::__set_state(array(
        'priority' => 30,
        'ttl' => 3600,
        'record_id' => 11889458,
        'rdata' => 'aspmx4.googlemail.com.',
        'type' => new models\loopia\ZoneRecordType('MX')
      )),
      7 => 
      models\loopia\ZoneRecord::__set_state(array(
        'priority' => 30,
        'ttl' => 3600,
        'record_id' => 11889459,
        'rdata' => 'aspmx5.googlemail.com.',
        'type' => new models\loopia\ZoneRecordType('MX')
      )),
      8 => 
      models\loopia\ZoneRecord::__set_state(array(
        'priority' => 0,
        'ttl' => 3600,
        'record_id' => 11889317,
        'rdata' => 'ns1.loopia.se.',
        'type' => new models\loopia\ZoneRecordType('NS')
      )),
      9 => 
      models\loopia\ZoneRecord::__set_state(array(
        'priority' => 0,
        'ttl' => 3600,
        'record_id' => 11889318,
        'rdata' => 'ns2.loopia.se.',
        'type' => new models\loopia\ZoneRecordType('NS')
      )),
    );
  }

  public function testUpdateWwwExampleCom()
  {
    $domainName = 'www.example.com';
    $ip = '127.0.0.1';

    $loopia = $this->getMockBuilder('services\loopia\Loopia')
      ->disableOriginalConstructor()
      ->getMock();

    $loopia
      ->expects($this->once())
      ->method('getZoneRecords')
      ->with('example.com', 'www')
      ->will($this->returnValue(
        array (
          models\loopia\ZoneRecord::__set_state(array(
            'priority' => 0,
            'ttl' => 3600,
            'record_id' => 11,
            'rdata' => '127.0.0.1',
            'type' => new models\loopia\ZoneRecordType('A')
          )),
          models\loopia\ZoneRecord::__set_state(array(
            'priority' => 0,
            'ttl' => 3600,
            'record_id' => 13,
            'rdata' => 'example.com',
            'type' => new models\loopia\ZoneRecordType('CNAME')
          )),
        )
      ));

    $loopia
      ->expects($this->at(1))
      ->method('removeZoneRecord')
      ->with('example.com', 'www', 11)
      ;
    $loopia
      ->expects($this->at(2))
      ->method('removeZoneRecord')
      ->with('example.com', 'www', 13)
      ;

    $expectedRecord = models\loopia\ZoneRecord::__set_state(array(
      'priority' => 0,
      'ttl' => 3600,
      'record_id' => 0,
      'rdata' => $ip,
      'type' => new models\loopia\ZoneRecordType('A')
    ));
    $loopia
      ->expects($this->once())
      ->method('addZoneRecord')
      ->with('example.com', 'www', $expectedRecord);

    $loopiaImpl = new services\dns\LoopiaImpl($loopia);
    $loopiaImpl->update($domainName, $ip);
  }

  public function testUpdateExampleCom()
  {
    $domainName = 'example.com';
    $ip = '127.0.0.1';

    $loopia = $this->getMockBuilder('services\loopia\Loopia')
      ->disableOriginalConstructor()
      ->getMock();

    $loopia
      ->expects($this->once())
      ->method('getZoneRecords')
      ->with('example.com', null)
      ->will($this->returnValue(self::getExampleComZoneRecords()));

    $loopia
      ->expects($this->once())
      ->method('removeZoneRecord')
      ->with('example.com', null, 11889407)
      ;

    $expectedRecord = models\loopia\ZoneRecord::__set_state(array(
      'priority' => 0,
      'ttl' => 3600,
      'record_id' => 0,
      'rdata' => $ip,
      'type' => new models\loopia\ZoneRecordType('A')
    ));
    $loopia
      ->expects($this->once())
      ->method('addZoneRecord')
      ->with('example.com', null, $expectedRecord);

    $loopiaImpl = new services\dns\LoopiaImpl($loopia);
    $loopiaImpl->update($domainName, $ip);
  }}


