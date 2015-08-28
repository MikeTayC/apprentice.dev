<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/14/15
 * Time: 4:37 PM
 *
 * Controller responsible for creating and saving new user registration
 **/
class User_Controller_Create extends Incubate_Controller_Abstract
{
    /**
     * Need to override parent construct Log in check is not required here
     **/
    public function __construct(){}

    /**
     * Handles post data and preps for saving to the database
     **/
    public function saveAction()
	{

        /**
         * request object instance
         **/
        $request = $this->_getRequest();

        /**
         * Check for post data
         **/
        if($request->isPost()){

            /**
             * load user model, user info related to tags and lessons
             * will be added through dispatch events
             **/
            $user = Bootstrap::getModel('user/model');

            //for all post information, add to user data
            foreach(array('name','email','groups') as $field) {
                $user->setData($field, $request->getPost($field));
            }

            /** get google id from session, add add it to user data **/
            $googleId = $this->_sessionGet('google_id');
            $user->setData('google_id', $googleId);

            /** Set default role of user to student **/
            $user->setRole('student');

            /** user will be saved to the database, dispatch events will tie any tags to the user **/
            $user->save();

            /**
             * this checkUSerDataForGoogleId will also store user information into the session i
             * to log them in: including user_id, logged in status, and admin status.
             **/
            if($user->checkUserDataForGoogleId($googleId)) {
                $this->_successFlash('You have been successfully added to Incubate!');
            }
            else {
                $this->_dangerFlash('There was a problem adding you to Incubate!');
                $this->headerRedirect('user', 'logout', 'index');
            }
        }
        $this->headerRedirect('schedule','calendar', 'index');
	}
}