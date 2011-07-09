<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'testing'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

require_once 'Zend/Loader/Autoloader.php';

$loader = Zend_Loader_Autoloader::getInstance();
$loader->suppressNotFoundWarnings(false);

function simpleAutoloader($className)
{
	$fullPath = APPLICATION_PATH . '/' . str_replace('\\', '/', $className) . '.php';
	//echo "[$fullPath]\n";
	if (file_exists($fullPath)) {
		include_once($fullPath);
	}
	else
		throw new Exception("File not found: ".$fullPath);
}
$loader->pushAutoloader('simpleAutoloader', 'models'); 
$loader->pushAutoloader('simpleAutoloader', 'services'); 

