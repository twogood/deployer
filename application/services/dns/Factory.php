<?php

namespace services\dns;

class Factory
{
  private $config;

  public function __construct($config)
  {
    $this->config = $config;
  }

  public function getImpl($site)
  {
    if (empty($site->dnsProvider))
      return null;
  
    switch (strtolower($site->dnsProvider))
    {
    case 'loopia':
      $loopiaFactory = new \services\loopia\Factory($this->config['loopia']);
      return new LoopiaImpl($loopiaFactory->get());
    default:
      throw new \Exception("Unknown DNS Provider: " . $site->dnsProvider);
    }
  }
}
