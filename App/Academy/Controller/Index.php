<?php
class Academy_Controller_Index extends Core_Controller_Abstract
{
    public function indexAction()
    {
        $view = $this->loadLayout();
        if(Core_Helpers_Cookie::exists(Core_Model_Config_Json::getModulesCookieConfig('cookie_name')) && !Core_Helpers_Session::exists(Core_Model_Config_Json::getModulesSessionConfig('session_name'))) {
            $hash = Core_Helpers_Cookie::get(Core_Model_Config_Json::getModulesCookieConfig('cookie_name'));
            $hashCheck = Core_Model_Database::getInstance()->get('users_session', array('hash', '=', $hash));

            if($hashCheck->count()) {
                $user = new Academy_Model_User($hashCheck->first()->user_id);
            }
        }
        else {
            $user = new Academy_Model_User();
        }

        if(isset($user) && $user->isLoggedIn()) {
            $view->getContent()->setUser($user->data());

            if($user->hasPermission('admin')) {
                echo '<p> You are an Admin!</p>';
            }

        }

        $view->render();
    }

    public function logoutAction()
    {
        $user = new Academy_Model_User();
        $user->logout();
        $this->redirect('Academy','Index', 'indexAction');
    }
}