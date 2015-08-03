<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/31/15
 * Time: 3:03 PM
 */
class Incubate_Controller_User extends Core_Controller_Abstract
{
    public function indexAction()
    {
        if (!Core_Model_Session::get('logged_in') || !Core_Model_Session::get('admin_status')) {
            $this->redirect('Incubate', 'Login', 'indexAction');
        }
        else {

            /*
             * instantiate user  model using boot strap factory,
             * string indicates which module and name of model to insantiate
             */
            $user = Bootstrap::getModel('incubate/user');

            //gets all users in user table
            $allUsers = $user->getAllUserDataFromUserTable();

            /*
             * load layout,
             * set data on the content block
             * render
             */
            $view = $this->loadLayout();
            $view->getContent()->setData('userData', $allUsers);
            $view->render();
        }
    }
}