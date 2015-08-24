<?php class Cron_Controller_Cron extends Core_Controller_Abstract {

	public function indexAction()
	{
        Bootstrap::dispatchEvent('cron_run', $this);
		// loop through config for cron

	}
}

