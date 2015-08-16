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
        $auth = new Core_Model_Auth();
        $auth->logout();
        $this->headerRedirect('incubate', 'login', 'index');
    }
}