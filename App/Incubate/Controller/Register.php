<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/14/15
 * Time: 4:37 PM
 */
class Incubate_Controller_Register extends Core_Controller_Abstract
{
    public function indexAction($googleId)
    {
        $view = $this->loadLayout($default = false);
        $user = new Incubate_Model_User();

        $userData = $user->get('user', array('google_id', '=', $googleId));

        Core_Model_Session::set('user_id', $userData->user_id);

        $view->render();
    }

    public function newAction()
    {
        if(!empty($_POST) && isset($_POST['group'])){
            $user = Bootstrap::getModel('incubate/user');

            $user_id = Core_Model_Session::get('user_id');

            $user->updateUserBasedOnUserId('user','user_id', $user_id, array(
               'group' => $_POST['group'],
               'joined' => date('Y:m:d')
            ));
        }
    }
}