<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 */
class User_Controller_Edit extends Incubate_Controller_Abstract
{
	public function adminAction($userId)
	{
		$this->_checkIfUserIsLoggedIn();
		$this->_checkIfUserIsAdmin();
		$this->_idCheck($userId, 'user');


		Bootstrap::getModel('incubate/user')->load($userId)->setRole('admin')->save();

		$this->_successFlash('Successfully made this user an admin');
		$this->_thisModuleRedirect('user');
	}
}