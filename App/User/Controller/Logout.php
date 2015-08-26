<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/29/15
 * Time: 3:04 PM
 */
class User_Controller_Logout extends Incubate_Controller_Abstract
{
    public function __construct(){}
    public function indexAction()
    {
        echo 'trouble';
        Bootstrap::getModel('user/model')->logout();
        $this->headerRedirect('incubate','login','index');
    }
}