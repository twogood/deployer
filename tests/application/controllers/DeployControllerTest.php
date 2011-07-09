<?php

class DeployControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testIndexAction()
    {
        $params = array('action' => 'index', 'controller' => 'Deploy', 'module' => 'default');
        $url = $this->url($this->urlizeOptions($params));
        $this->dispatch($url);

        // assertions
        $this->assertModule($params['module']);
        $this->assertController($params['controller']);
        $this->assertAction($params['action']);
        $this->assertQueryContentContains(
            'div#view-content p',
            'View script for controller <b>' . $params['controller'] . '</b> and script/action name <b>' . $params['action'] . '</b>'
            );
    }

    public function testDeployAction()
    {
	    $deployService = $this->getMockBuilder('services\Deploy')
		    ->disableOriginalConstructor()
		    ->getMock();
	$deployService
		->expects($this->once())
		->method('deploy')
		->with('test-site', 'test-host');

	$serviceFactory = $this->getMockBuilder('services\ServiceFactory')
		    ->disableOriginalConstructor()
		    ->getMock();
	$serviceFactory
		->expects($this->any())
		->method('createDeployService')
		->will($this->returnValue($deployService))
		;


	Zend_Registry::set('serviceFactory', $serviceFactory);
	

	$this->request->setMethod('POST')
		->setPost(array(
					'site' => 'test-site',
					'host' => 'test-host'
			       ));

        $params = array('action' => 'deploy', 'controller' => 'Deploy', 'module' => 'default');
        $url = $this->url($this->urlizeOptions($params));
        $this->dispatch($url);

/*
	$content    = $this->response->outputBody();
	echo "\n[$content]\n";
*/
        
        // assertions
        $this->assertModule($params['module']);
        $this->assertController($params['controller']);
        $this->assertAction($params['action']);
        $this->assertQueryContentContains(
            'div#view-content p',
            'View script for controller <b>' . $params['controller'] . '</b> and script/action name <b>' . $params['action'] . '</b>'
            );
    }


}





