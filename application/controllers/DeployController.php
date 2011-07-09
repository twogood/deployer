<?php

class DeployController extends Zend_Controller_Action
{
    private $serviceFactory;

    public function init()
    {
	$this->serviceFactory = Zend_Registry::get('serviceFactory');
    }

    protected function makeUrl($action, $params = array())
    {
	    $params['controller'] = 'deploy';
	    $params['action'] = $action;
	    return $this->view->url($params, null, true);
    }

    public function indexAction()
    {
	$repository = $this->serviceFactory->getRepository();

	$deployForm = new forms\DeployForm();
	$deployForm->setSiteNames($repository->getSiteNames());
	$deployForm->setHostNames($repository->getHostNames());
	$deployForm->setAction($this->makeUrl('deploy'));

	$this->view->deployForm = $deployForm;
    }

    public function deployAction()
    {
	if (!$this->getRequest()->isPost())
	{
		// TODO: redirect to index
		throw new Exception("Not POST");
	}
	
	$deployService = $this->serviceFactory->getDeployService();
	$repository = $this->serviceFactory->getRepository();

	$deployForm = new forms\DeployForm();
	$deployForm->setSiteNames($repository->getSiteNames());
	$deployForm->setHostNames($repository->getHostNames());

	if (!$deployForm->isValid($this->_getAllParams()))
	{
		// TODO: redirect to index
		throw new Exception("Invalid form: ".
		print_r($this->_getAllParams(), true));
	}

	$siteName = $deployForm->getValue('site');
	$hostName = $deployForm->getValue('host');

	$deployService->deploy($siteName, $hostName);
    }


}



