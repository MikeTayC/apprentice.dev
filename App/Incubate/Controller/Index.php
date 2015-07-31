<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/17/15
 * Time: 10:26 AM
 */
class Incubate_Controller_Index extends Core_Controller_Abstract
{
    public $view;
    public $auth;
    public $googleClient;

    public function __construct()
    {
        if(!Core_Helpers_Session::get('logged_in')) {
            $this->redirect('Incubate', 'Login', 'actionIndex');
        }
    }
    public function indexAction()
    {
            $view = $this->loadLayout();

            $view->render();
    }
}