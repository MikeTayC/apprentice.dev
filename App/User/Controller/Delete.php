<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 */
class User_Controller_Delete extends Incubate_Controller_Abstract
{
public function indexAction($userId)
{
		$this->_checkIfUserIsLoggedIn();
		$this->_checkIfUserIsAdmin();
		$this->_idCheck($userId, 'user');

		Bootstrap::getModel('incubate/user')->load($userId)->delete();

	//need to delete users associated tags
		$event = Bootstrap::getModel('core/event')->setUser($userId);
		Bootstrap::dispatchEvent('delete_user_after', $event);

		$this->_successFlash('User successfully removed');
		$this->_thisModuleRedirect('user');
	}
}