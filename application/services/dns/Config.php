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

    $ip = gethostbyname($host->name);

    foreach ($site->domainNames as $domainName)
    {
      $impl->update($domainName, $ip);
    }
  }
}
