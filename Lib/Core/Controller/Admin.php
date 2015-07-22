<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/16/15
 * Time: 11:31 AM
 *
 * child class will have ability to create/edit/delete users from database tables,
 */
 class Core_Controller_Admin extends Core_Controller_Abstract
{
    protected $_db;
    public $user;
    public function __construct()
    {
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

     public function isLoggedIn()
     {
     }

}