<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/14/15
 * Time: 4:37 PM
 */
class Incubate_Controller_Register extends Incubate_Controller_Abstract
{
    public function indexAction()
    {
        //load register form that will allow user to pick there job role
		$name = $this->_sessionGet('googleDisplayName');
		$email = $this->_sessionGet('email');

        $view = $this->loadLayout($default = false);
		$view->getContent()->setName($name)->setEmail($email);
        $view->render();

        //delete unnessary session information after the page is rendered
        $this->_sessionDelete('email');
        $this->_sessionDelete('googleDisplayName');
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

            //save the new user to the database;
            $user->save();

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