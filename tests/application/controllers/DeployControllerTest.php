<?php

class DeployControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

  public function setUp()
  {
    if (!method_exists($this, 'urlizeOptions')
      || !method_exists($this, 'url'))
    {
      $this->markTestSkipped("Old Zend_Test_PHPUnit_ControllerTestCase?");
    }

    $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
    parent::setUp();
  }

  public function testIndexAction()
  {
    $siteNames = array('site1', 'site2', 'site3', 'site4', 'site5');
    $hostNames = array('host1', 'host2', 'host3');

    $repository = $this->getMockBuilder('services\Repository')
      ->disableOriginalConstructor()
      ->getMock();
    $repository
      ->expects($this->once())
      ->method('getSiteNames')
      ->will($this->returnValue($siteNames));
    $repository
      ->expects($this->once())
      ->method('getHostNames')
      ->will($this->returnValue($hostNames));

    $serviceFactory = $this->getMockBuilder('services\ServiceFactory')
      ->disableOriginalConstructor()
      ->getMock();
    $serviceFactory
      ->expects($this->any())
      ->method('getRepository')
      ->will($this->returnValue($repository));

    Zend_Registry::set('serviceFactory', $serviceFactory);

    $deployForm = new forms\DeployForm();
    $deployForm->setSiteNames($siteNames);
    $deployForm->setHostNames($hostNames);
    $deployForm->setAction('/deploy/deploy');



    $params = array('action' => 'index', 'controller' => 'Deploy', 'module' => 'default');
    $url = $this->url($this->urlizeOptions($params));
    $this->dispatch($url);

    $content = $this->response->outputBody();

    // assertions
    $this->assertModule($params['module']);
    $this->assertController($params['controller']);
    $this->assertAction($params['action']);
    $this->assertQueryContentContains(
      'div#view-content p',
      'View script for controller <b>' . $params['controller'] . '</b> and script/action name <b>' . $params['action'] . '</b>'
    );

    $this->assertNotEquals(false, strpos($content, (string)$deployForm));
  }

  public function testDeployAction()
  {
    $repository = $this->getMockBuilder('services\Repository')
      ->disableOriginalConstructor()
      ->getMock();
    $repository
      ->expects($this->once())
      ->method('getSiteNames')
      ->will($this->returnValue(array('site1', 'site2', 'site3', 'site4', 'site5')));
    $repository
      ->expects($this->once())
      ->method('getHostNames')
      ->will($this->returnValue(array('host1', 'host2', 'host3')));

    $deployService = $this->getMockBuilder('services\Deploy')
      ->disableOriginalConstructor()
      ->getMock();
    $deployService
      ->expects($this->once())
      ->method('deploy')
      ->with('site4', 'host2');

    $serviceFactory = $this->getMockBuilder('services\ServiceFactory')
      ->disableOriginalConstructor()
      ->getMock();
    $serviceFactory
      ->expects($this->any())
      ->method('getDeployService')
      ->will($this->returnValue($deployService))
      ;
    $serviceFactory
      ->expects($this->any())
      ->method('getRepository')
      ->will($this->returnValue($repository));

    // TODO
    $dnsConfig = null;
    $serviceFactory
      ->expects($this->once())
      ->method('getDnsConfig')
      ->will($this->returnValue($dnsConfig));

    Zend_Registry::set('serviceFactory', $serviceFactory);

    $this->request->setMethod('POST')
      ->setPost(array(
        'site' => 'site4',
        'host' => 'host2',
      ));

    $params = array('action' => 'deploy', 'controller' => 'Deploy', 'module' => 'default');
    $url = $this->url($this->urlizeOptions($params));
    $this->dispatch($url);

    $content    = $this->response->outputBody();
    //echo "\n[$content]\n";

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

