<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/18/15
 * Time: 3:32 PM
 */
class Incubate_Controller_User extends Core_Controller_Authorization
{
    public $user;
    public $googleUser;
    public function __construct()
    {
        if(!$this->isLoggedIn())
        {
            $this->redirect('Incubate', 'index', 'indexAction');
        }
        else {
            $this->googleUser = Core_Model_Request::getInstance()->getGoogle();
            $this->user = Core_Model_Request::getInstance()->getUser();
        }
    }

    public function indexAction()
    {
        $view = $this->loadLayout();

        $view->getContent()->setUser($this->user);
        $view->getContent()->setGoogle($this->googleUser);

        $view->render();
    }
}