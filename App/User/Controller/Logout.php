<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/29/15
 * Time: 3:04 PM
 */
class User_Controller_Logout extends Incubate_Controller_Abstract
{
    public function indexAction()
    {
        $user = Bootstrap::getModel('incubate/user');
        $user->logout();
        $this->_thisModuleRedirect('login');
    }
}