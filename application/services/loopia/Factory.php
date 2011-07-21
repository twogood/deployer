<?php

namespace services\loopia;

class Factory
{
  private $config;

  public function __construct($config)
  {
    $this->config = $config;
  }

  public function get()
  {
    $server = new \services\loopia\Server();
    return new \services\loopia\Loopia($server, $this->config);
  }

}

