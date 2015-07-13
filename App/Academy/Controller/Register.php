<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/8/15
 * Time: 1:52 PM
 */
class Academy_Controller_Register extends Core_Controller_Abstract
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->render();
    }
    public function registerAction()
    {
        if(isset($_POST)){
            Core_Model_Validator::check($_POST);
            if(Core_Model_Validator::passed()){
                $user = new Academy_Model_User();

                $salt = Core_Helpers_Hash::salt(32);
                try {
                    $user->create(array(
                       'username' => Core_Helpers_Input::get('username'),
                       'password' => Core_Helpers_Hash::make(Core_Helpers_Input::get('password'), $salt),
                       'salt' => $salt,
                       'name' =>  Core_Helpers_Input::get('name'),
                       'joined' => date('Y-m-d H:i:s'),
                       'connect' => 1,

                    ));

                    Core_Helpers_Session::flash('home', 'You have been registered and can now log in');
                } catch(Exception $e) {
                    //probably be better to redirect with error message
                    die($e->getMessage());
                }


            }
            else {
                print_r(Core_Model_Validator::errors());
            }

        }

        $this->redirect('Academy','Index','indexAction');
    }
}