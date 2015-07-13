<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/13/15
 * Time: 10:35 AM
 */
class Academy_Controller_Password extends Core_Controller_Abstract
{
    public function indexAction()
    {
        $view = $this->loadLayout();

        $user = new Academy_Model_User(Core_Helpers_Session::get(Core_Model_Config_Json::getModulesSessionConfig('session_name')));
        if(!$user->isLoggedIn()){
            $this->redirect('Academy','Index', 'indexAction');
        }

        if (Core_Helpers_Input::exists()) {
            Core_Model_Validator::check($_POST);
            if(Core_Model_Validator::passed()) {
                if(Core_Helpers_Hash::make(Core_Helpers_Input::get('current_password'), $user->data()->salt) !== $user->data()->password) {
                    echo 'Your password is incorrect!';
                }
                else {
                    $salt = Core_Helpers_Hash::salt(32);
                    $user->update(array(
                        'password' => Core_Helpers_Hash::make(Core_Helpers_Input::get('new_password'), $salt),
                        'salt' => $salt
                    ));
                    Core_Helpers_Session::flash('home', 'Your password has been changed..');
                    $this->redirect('Academy','Index', 'indexAction');

                }
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