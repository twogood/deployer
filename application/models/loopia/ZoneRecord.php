<?php

namespace models\loopia;

class ZoneRecord
{
  public $priority;
  public $type; // ZoneRecordType
  public $ttl;
  public $record_id;
  public $rdata;

  public function __construct(ZoneRecordType $type, $rdata, $ttl = 3600, $priority = 0, $record_id = 0)
  {
    $this->priority = $priority;
    $this->ttl = $ttl;
    $this->type = $type;
    $this->rdata = $rdata;
    $this->record_id = $record_id;
  }

  static function __set_state(array $array) 
  {
    $result = new self(
      new ZoneRecordType((string)$array['type']),
      $array['rdata'],
      $array['ttl'],
      $array['priority'],
      $array['record_id']
      );
    return $result;
  }

}
