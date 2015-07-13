<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/9/15
 * Time: 1:13 PM
 */
class Academy_Controller_Login extends Core_Controller_Abstract
{
    public function indexAction()
    {
        if (Core_Helpers_Input::exists()) {
            Core_Model_Validator::check($_POST);
            if(Core_Model_Validator::passed()) {
                $user = new Academy_Model_User();

                $remember = (Core_Helpers_Input::get('remember')) === 'on' ? true : false;
                $login = $user->login(Core_Helpers_Input::get('username'), Core_Helpers_Input::get('password'), $remember);

                if($login) {
                    $this->redirect('Academy', 'Index', 'indexAction');
                }
                else {
                    echo 'fail';
                }
            }
            else {
                foreach(Core_Model_Validator::errors() as $error){
                    echo $error, '<br>';
                }
            }
        }
        else {
            $this->loadLayout();
            $this->render();
        }
    }
}