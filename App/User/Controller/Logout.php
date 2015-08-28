<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/29/15
 * Time: 3:04 PM
 *
 * Controller logs user out,
 *
 **/
class User_Controller_Logout extends Incubate_Controller_Abstract
{
    /** must override parent, always need to be able to loggout */
    public function __construct(){}
    public function indexAction()
    {
        /** logout function will delete all users current session information */
        Bootstrap::getModel('user/model')->logout();

        /** redirect back to login page */
        $this->headerRedirect('incubate','login','index');
    }
}