<?php

class IndexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testDeploy()
    {
/*
	    $this->request->setMethod('POST')
		    ->setPost(array(
					    'siteName' => 'testsite',
					    'host' => 'testhost',
				   ));

	    $this->dispatch('/deploy');
	    $this->assertController('index');
	    $this->assertAction('deploy');
*/
    }

}

