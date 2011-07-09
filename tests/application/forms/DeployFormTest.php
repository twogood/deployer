<?php

class DeployFormTest extends PHPUnit_Framework_TestCase
{
	
	public static function formHasSubmitElement($form)
	{
		$elements = $form->getElements();
		$hasSubmit = false;
		foreach ($elements as $element)
		{
			if ($element->getType() == 'Zend_Form_Element_Submit')
			{
				$hasSubmit = true;
				break;
			}
		}
		return $hasSubmit;
	}

	public function assertFormHasSubmit($form)
	{
		$this->assertTrue(
			self::formHasSubmitElement($form), 
			'Form has submit element');
	}

	public function testDeployForm()
	{
		$siteNames = array('site1', 'site2', 'site3', 'site4', 'site5');
		$hostNames = array('host1', 'host2', 'host3');

		$form = new forms\DeployForm;
		$form->setSiteNames($siteNames);
		$form->setHostNames($hostNames);

		$this->assertEquals('post', $form->getMethod());

		$site = $form->getElement('site');
		$this->assertNotNull($site, 'site form element');
		$this->assertInstanceOf('Zend_Form_Element', $site);
		$this->assertTrue($site->isRequired());
		$siteOptions = $site->getMultiOptions();
		$this->assertEquals(5, count($siteOptions));
		$this->assertEquals($siteNames[0], $siteOptions[$siteNames[0]]);

		$host = $form->getElement('host');
		$this->assertNotNull($host, 'host form element');
		$this->assertInstanceOf('Zend_Form_Element', $host);
		$this->assertTrue($host->isRequired());
		$hostOptions = $host->getMultiOptions();
		$this->assertEquals(3, count($hostOptions));
		$this->assertEquals($hostNames[0], $hostOptions[$hostNames[0]]);
		
		$this->assertFormHasSubmit($form);
		


	}
}

