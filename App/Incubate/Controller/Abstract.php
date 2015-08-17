<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/17/15
 * Time: 11:29 AM
 */
abstract class Incubate_Controller_Abstract extends Core_Controller_Abstract
{

    public function checkIfUserIsLoggedIn()
    {
        if(!Core_Model_Session::get('logged_in')) {
            Core_Model_Session::dangerFlash('You are not logged in!');
            $this->headerRedirect('incubate', 'login', 'index');
            exit;
        }
    }

    public function checkIfUserIsAdmin()
    {
        if(!Core_Model_Session::get('admin_status')) {
            Core_Model_Session::dangerflash('error', 'Admins Only');
            $this->headerRedirect('incubate','index','index');
            exit;
        }
    }
}