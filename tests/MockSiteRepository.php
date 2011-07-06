<?php

class MockSiteRepository
{
	private $site;

	public function getSite($siteName)
	{
		if (!isset($this->site) || $siteName != $this->site->name)
			throw new InvalidArgumentException("Unknown site");
		return $this->site;
	}

	public function setSite($site)
	{
		$this->site = $site;
	}

}
