<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/17/15
 * Time: 10:26 AM
 */
class Incubate_Controller_Index extends Incubate_Controller_Abstract
{
    public function indexAction()
    {
        if(!Core_Model_Session::get('logged_in')) {
            $this->redirect('Incubate', 'Login', 'indexAction');
        }
        else {
			$this->loadLayout();

            $this->flashCheck();

			$this->render();

		}
    }
}