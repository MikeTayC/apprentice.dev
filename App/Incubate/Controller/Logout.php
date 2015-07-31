<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/29/15
 * Time: 3:04 PM
 */
class Incubate_Controller_Logout extends Core_Controller_Abstract
{
    public function indexAction()
    {
        $auth = new Core_Helpers_GoogleAuth();
        $auth->logout();
        $this->redirect('Incubate', 'Index', 'indexAction');
    }
}