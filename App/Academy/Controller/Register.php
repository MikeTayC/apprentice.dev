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
    }
    public function registerAction()
    {
        if(isset($_POST)){
            $validator = new Academy_Model_Validation();
            $validator->check($_POST);
            if($validator->passed()){
//                $name = $_POST['name'];
//                $username = $_POST['username'];
//                $password = $_POST['password'];
//                $newUser = Core_Model_Database::getInstance()->insert('users', array(
//                    'name' => $name,
//                    'username' => $username,
//                    'password' => $password
//                ));
                $user = new Academy_Model_User();

                $salt = Core_Helpers_Hash::salt(32);
                try {
                    $user->create(array(
                       'username' => $_POST['username'],
                       'password' => Core_Helpers_Hash::make($_POST['password'], $salt),
                       'salt' => $salt,
                       'name' => $_POST['name'],
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
                print_r($validator->errors());
            }

        }

        $this->redirect('Academy','Index','indexAction');
    }
}