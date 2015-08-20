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
        $user = Bootstrap::getModel('incubate/user');
        $user->logout();
        $this->_thisModuleRedirect('login');
    }
}