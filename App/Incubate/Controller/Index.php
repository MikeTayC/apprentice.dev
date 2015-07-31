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
        if(!Core_Helpers_Session::get('logged_in')) {
            $this->redirect('Incubate', 'Login', 'indexAction');
        }
        else {
            $view = $this->loadLayout();

            $view->render();
        }
    }
}