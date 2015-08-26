<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:28 AM
 */
class User_Controller_View extends Incubate_Controller_Abstract
{
	public function indexAction()
	{
        $this->_checkIfUserIsAdmin();
        $this->_flashCheck();

        /*
         * instantiate user  model using boot strap factory,
         * string indicates which module and name of model to insantiate
         * loads all students
         *
         */
        $allUsers = Bootstrap::getModel('user/model')->loadAllStudents();

        /*
         * load layout,
         * set data on the content block
         * render
         */
        $view = $this->loadLayout();

        $view->getContent()->setData('userData', $allUsers);

        $view->render();
	}

	public function profileAction($userId)
	{
        $this->_idCheck($userId, 'user');
        $this->userProfileCheck($userId);
        $this->_flashCheck();


        $user = Bootstrap::getModel('user/model')->load($userId)->loadProfile();

        $view = $this->loadLayout();
        $view->getContent()->setData('userData', $user);
        $view->render();
	}

	public function adminAction()
	{
        $this->_checkIfUserIsAdmin();

        $allAdmins = Bootstrap::getModel('user/model')->loadAllAdmins();

        $view = $this->loadLayout();
        $view->getContent()->setAdmins($allAdmins);
        $view->render();
	}
}