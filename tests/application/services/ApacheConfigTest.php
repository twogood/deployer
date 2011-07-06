<?php

// XXX: can't get autoloading to work :-(

require_once APPLICATION_PATH . '/services/ApacheConfig.php';

class ApacheConfigTest extends PHPUnit_Framework_TestCase
{
/*
    public function testPushAndPop()
    {
        $stack = array();
        $this->assertEquals(0, count($stack));
 
        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertEquals(1, count($stack));
 
        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }
*/

    private static function trimAllLines($str)
    {
	// also removes empty lines
	return preg_replace('/[\s]*[\n][\s]*/s', "\n", $str);
    }
	
    public function testCreateConfigWithoutAlias()
    {
	$apacheConfigService = new Application_Service_ApacheConfig();

	$actual = $apacheConfigService->createDefaultConfig('test', array('test1'));
	$this->assertNotNull($actual);

$expected = <<<EOF
# This file was auto-generated by deployer, do not edit!
  <Directory /var/www/test/public>
  AllowOverride All
  </Directory>

  <VirtualHost *>
  DocumentRoot /var/www/test/public
  ServerName test1
  ErrorLog /var/log/apache2/test-error_log
  CustomLog /var/log/apache2/test-access_log combined env=!dontlog
  </VirtualHost>
EOF;

	$expected = self::trimAllLines($expected);
	$actual = self::trimAllLines($actual);

	$this->assertEquals($expected, $actual);
    }


    public function testCreateConfigWithAlias()
    {
	$apacheConfigService = new Application_Service_ApacheConfig();

	$actual = $apacheConfigService->createDefaultConfig('test', array('test1', 'test2'));
	$this->assertNotNull($actual);

$expected = <<<EOF
# This file was auto-generated by deployer, do not edit!
  <Directory /var/www/test/public>
  AllowOverride All
  </Directory>

  <VirtualHost *>
  DocumentRoot /var/www/test/public
  ServerName test1
  ServerAlias test2
  ErrorLog /var/log/apache2/test-error_log
  CustomLog /var/log/apache2/test-access_log combined env=!dontlog
  </VirtualHost>
EOF;

	$expected = self::trimAllLines($expected);
	$actual = self::trimAllLines($actual);

	$this->assertEquals($expected, $actual);
    }

    public function testCreateConfigWithExtraConfig()
    {
	$apacheConfigService = new Application_Service_ApacheConfig();

	$actual = $apacheConfigService->createDefaultConfig('test', array('test1', 'test2'), 
		'Include /etc/phpmyadmin/apache.conf');
	$this->assertNotNull($actual);

$expected = <<<EOF
# This file was auto-generated by deployer, do not edit!
<Directory /var/www/test/public>
  AllowOverride All
</Directory>

<VirtualHost *>
  DocumentRoot /var/www/test/public
  ServerName test1
  ServerAlias test2
  ErrorLog /var/log/apache2/test-error_log
  CustomLog /var/log/apache2/test-access_log combined env=!dontlog
  Include /etc/phpmyadmin/apache.conf
</VirtualHost>
EOF;

	$expected = self::trimAllLines($expected);
	$actual = self::trimAllLines($actual);

	$this->assertEquals($expected, $actual);
    }


}

