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
			Core_Model_Session::flash('message');

			$view = $this->loadLayout();

            if(Core_Model_Session::get('admin_status')) {
				$view->getContent()->setTemplate('App/Incubate/View/Template/.phtml');
			}
			else {
				$userId = Core_Model_Session::get('user_id');
				$this->redirect('Incubate', 'User', 'profileAction', $userId);
			}

			$view->render();
        }
    }
}