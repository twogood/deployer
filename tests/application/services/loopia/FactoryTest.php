<?php

class LoopiaFactoryTest extends PHPUnit_Framework_TestCase
{

  public function testGet()
  {
    $config = array(
      'username' => '',
      'password' => '',
    );
    $factory = new \services\loopia\Factory($config);
    $loopia = $factory->get();

    $this->assertInstanceOf('services\loopia\Loopia', $loopia);
  }

}

