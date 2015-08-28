<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:28 AM
 *
 * Controller handles viewing any pages regarding user information
 *
 * User can view profiles, so we extends from incubate_controller_abstracct
 * and individually check for admin status on admin specific views
 *
 * User must be logged in
 **/
class User_Controller_View extends Incubate_Controller_Abstract
{

    /**
     * Admins only
     *
     * Quick veiw of all users, their progress, and incubator time,
     * and option to individually manage
     **/
	public function indexAction()
	{
        /** admin check and message check */
        $this->_checkIfUserIsAdmin();
        $this->_flashCheck();

        /**
         * instantiate user  model using boot strap factory,
         * string indicates which module and name of model to insantiate
         * loads all students, dispatched events, will still accessory
         * information
         **/
        $allUsers = Bootstrap::getModel('user/model')->loadAllStudents();

        /** load layout, set data on the content block render **/
        $view = $this->loadLayout();
        $view->getContent()->setData('userData', $allUsers);
        $view->render();
	}

    /**
     * Admins and owner of user profile only
     *
     * @param $userId
     **/
	public function profileAction($userId)
	{
        /** Verify user exists */
        $this->_idCheck($userId, 'user');

        /** Checks if owner of profile, or an admin before allowing access */
        $this->userProfileCheck($userId);

        /** Message check */
        $this->_flashCheck();


        /** @var  $user
         * Loads instance of current user,
         * loadProfile() dispatches events, that will add tags, and completed courses
         * onto $user.
         **/
        $user = Bootstrap::getModel('user/model')->load($userId)->loadProfile();

        /** @var $view loads layout, binds user data, renders */
        $view = $this->loadLayout();
        $view->getContent()->setData('userData', $user);
        $view->render();
	}

    /**
     * View all current admins, and view their contact information
     * Cannot edit other admins
     **/
	public function adminAction()
	{
        /** Check if admin **/
        $this->_checkIfUserIsAdmin();

        /** @var $allAdmins all current admins will be loaded*/
        $allAdmins = Bootstrap::getModel('user/model')->loadAllAdmins();

        /** Load view. bind data, render */
        $view = $this->loadLayout();
        $view->getContent()->setAdmins($allAdmins);
        $view->render();
	}
}