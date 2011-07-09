<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initConfig()
	{
		Zend_Registry::set('config', $this->getOptions());
	}

	// @codeCoverageIgnoreStart
	public static function simpleAutoloader($className)
	{
		$fullPath = APPLICATION_PATH . '/' . str_replace('\\', '/', $className) . '.php';
		if (file_exists($fullPath)) {
			include_once($fullPath);
		}
	}
	// @codeCoverageIgnoreEnd

	protected function _initAutoload() {
		$loader = Zend_Loader_Autoloader::getInstance();
		$loader->pushAutoloader(array('Bootstrap', 'simpleAutoloader'), 'models'); 
		$loader->pushAutoloader(array('Bootstrap', 'simpleAutoloader'), 'services'); 

	}	

}

