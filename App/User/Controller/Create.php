<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/14/15
 * Time: 4:37 PM
 */
class User_Controller_Create extends Incubate_Controller_Abstract
{
    public function __construct(){}
	public function saveAction()
	{
        $request = $this->_getRequest();
        if($request->isPost()){

            //load user model
            $user = Bootstrap::getModel('user/model');

            //for all post information, add to user data
            foreach(array('name','email','groups') as $field) {
                $user->setData($field, $request->getPost($field));
            }

            //get google id from session, add add it to user data
            $googleId = $this->_sessionGet('google_id');
            $user->setData('google_id', $googleId);

            //set default role
            $user->setRole('student');

            //user will be saved to the database, dispatch events will tie any tags to the user
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
                $this->headerRedirect('user', 'logout', 'index');
                exit;
            }
        }
        $this->headerRedirect('incubate','calendar', 'index');
	}
}