<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/17/15
 * Time: 10:26 AM
 */
class Incubate_Controller_Index extends Core_Controller_Abstract
{
    public function indexAction()
    {
        if(!Core_Model_Session::get('logged_in')) {
            $this->redirect('Incubate', 'Login', 'indexAction');
        }
        else {
			$this->loadLayout();
			echo Core_Model_Session::successFlash('message');
			echo Core_Model_Session::dangerFlash('error');
			$this->render();

		}
    }
}