<?php
/*
 * Test class
 */
class Admin_Controller_Login extends Core_Controller_Abstract
{
    public $auth;

    public function indexAction()
    {
        $googleClient = new Google_Client();
        $this->auth = new Core_Helpers_GoogleAuth($googleClient);

        if($this->auth->checkRedirectCode()){
//            $this->redirect('Admin', 'Login', 'indexAction');
            header('Location: http://apprentice.dev/admin/login/index');
        }

        if(!$this->auth->isLoggedIn()) {
            echo '<a href="' . $this->auth->getAuthUrl() . '">Sign in with Google</a>';
            var_dump($googleClient);
        }
        else {
            echo 'You are signed in.<a href="../login/logout">Sign out</a>';
            var_dump($googleClient);
        }
    }

    public function logoutAction()
    {

        $this->auth->logout();

        $this->redirect('Admin', 'login', 'indexAction');
    }
}
