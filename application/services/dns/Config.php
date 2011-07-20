<?php

namespace services\dns;

class Config
{
  private $factory;

  public function __construct($factory)
  {
    $this->factory = $factory;
  }

  public function update($site, $host)
  {
    $impl = $this->factory->getImpl($site);
    return $impl->update($site, $host);
  }
}
