<?php
class Academy_Controller_Profile extends Core_Controller_Abstract
{
    public function indexAction()
    {
        $view = $this->loadLayout();

        if(!$username = Core_Helpers_Input::get('user')){
            $this->redirect('Academy', 'Index', 'indexAction');
        }
        else {
            $user = new Academy_Model_User($username);

            if(!$user) {
                $this->redirect('Core', 'Error', 'errorAction');
            }
            else {
             $view->getContent()->setUser($user->data());
            }
        }
        $view->render();
    }

}