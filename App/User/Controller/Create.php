<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/14/15
 * Time: 4:37 PM
 */
class User_Controller_Create extends Incubate_Controller_Abstract
{
	public function formAction($email, $displayName)
	{
		$view = $this->loadLayout($default = false);
		$view->getContent()->setName($displayName)->setEmail($email);
		$view->render();
	}

	public function newAction()
	{
		$request = $this->_getRequest();
		if($request->isPost()){

			//load user model
			$user = Bootstrap::getModel('incubate/user');

			//for all post information, add to user data
			foreach(array('name','email','groups') as $field) {
				$user->setData($field, $request->getPost($field));
			}

			//get google id from session, add add it to user data
			$googleId = $this->_sessionGet('google_id');
			$user->setData('google_id', $googleId);

			//set default role
			$user->setRole('student');

			/*
			 * this dispatch will save the new user to the user table,
			 * the associative group tag will be added to the UserTagMap
			 */
			$event = Bootstrap::getModel('core/event')->setUser($user);
			Bootstrap::dispatchEvent('user_register_after', $event);

			/*
			 * this checkUSerDataForGoogleId will also store user information into the session i
			 * to log them in: including user_id, logged in status, and admin status.
			 */
			if($user->checkUserDataForGoogleId($googleId)) {
				$this->_successFlash('You have been successfully added to Incubate!');
			}
			else {
				$this->_dangerFlash('There was a problem adding you to Incubate!');
				$this->headerRedirect('incubate', 'logout', 'index');
				exit;
			}
		}
		$this->_thisModuleRedirect('index');
	}
}