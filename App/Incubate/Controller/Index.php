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
        if(!$this->_sessionGet('logged_in')) {
            $this->redirect('Incubate', 'Login', 'indexAction');
        }
        else {
			$this->loadLayout();

            $this->_flashCheck();

			$this->render();

		}
    }
}