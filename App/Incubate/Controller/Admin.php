<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/23/15
 * Time: 10:07 AM
 */
class Incubate_Controller_Admin extends Incubate_Controller_Authorization
{
    public $user;
    public function __construct()
    {
        parent::__construct();

        $this->setUserData();
        $this->checkAdminStatus();
    }

    public function setUserData()
    {
        if(!$this->user = Core_Helpers_Session::get('userData')){
            $this->redirect('Incubate', 'Index','indexAction');
        }
    }

    public function checkAdminStatus()
    {
        if($this->user->role != 'admin'){
            $this->redirect('Incubate', 'index', 'indexAction');
        }
    }
}