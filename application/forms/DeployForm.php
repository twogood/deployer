<?php

namespace forms;

class DeployForm extends \Zend_Form
{
	public function init()
	{
		$this->setMethod('post');
		
		$site = new \Zend_Form_Element_Select('site', array(
			'label' => 'Site',
			'size' => 10
		));
		$site->setRequired();

		$host = new \Zend_Form_Element_Select('host', array(
			'label' => 'Host',
			'size' => 10
		));
		$host->setRequired();

		$submit = $this->createElement('submit', 'deploy', array(
			'label' => 'Deploy'));

		$this
			->addElement($site)
			->addElement($host)
			->addElement($submit)
		;
	}

	public function setSiteNames($sites)
	{
		$this->getElement('site')->setMultiOptions(array_combine($sites, $sites));
	}

	public function setHostNames($hosts)
	{
		$this->getElement('host')->setMultiOptions(array_combine($hosts,$hosts));
	}

}
