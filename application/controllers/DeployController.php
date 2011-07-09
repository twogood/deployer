<?php

class DeployController extends Zend_Controller_Action
{
    public function init()
    {
    }

    public function indexAction()
    {
        // action body
    }

    public function deployAction()
    {
	$siteName = $this->_getParam('site');
	$hostName = $this->_getParam('host');

	$serviceFactory = Zend_Registry::get('serviceFactory');
	$deployService = $serviceFactory->createDeployService();

	$deployService->deploy($siteName, $hostName);

    }


}



