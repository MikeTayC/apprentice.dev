<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/13/15
 * Time: 10:35 AM
 */
class Academy_Controller_Update extends Core_Controller_Abstract
{
    public function indexAction()
    {
        $view = $this->loadLayout();

        $user = new Academy_Model_User(Core_Model_Session::get(Core_Model_Config_Json::getModulesSessionConfig('session_name')));
        if($user->isLoggedIn()){
            $view->getContent()->setUser($user->data());
        }

        if (Core_Helpers_Input::exists()) {
            Core_Model_Validator::check($_POST);
            if(Core_Model_Validator::passed()) {
                try {
                    $user->update(array(
                        'name' => Core_Helpers_Input::get('name')
                    ));
                } catch(Exception $e) {
                    die($e->getMessage());
                }
                $this->redirect('Academy','Index', 'indexAction');
            }
            else {
                foreach(Core_Model_Validator::errors() as $error){
                    echo $error, '<br>';
                }
            }
        }
        else {
            $view->render();
        }
    }
}