<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/29/15
 * Time: 1:38 PM
 *
 */
class Incubate_Controller_Login extends Incubate_Controller_Abstract
{

    public function __construct(){}
    public function indexAction()
    {
        /*
         * Default layout is set to false, we want the default google sign in landing page showing that the user must
         * sign in with a blue acorn google plus account
         */
        $view = $this->loadLayout($default = false);
        $this->_flashCheck();
        /*
         * instantiate google client and our authorization class and user model class
         */
        $googleClient = new Google_Client();
        $user = Bootstrap::getModel('user/model');
        $auth = new Core_Model_Auth($user, $googleClient);

        /*
         * call to auth model to make sure the redirect code is set in the get request, routed from sign in url
         */
        if($auth->checkRedirectCode()) {

            //set current user information for access
            $auth->setGooglePlusInfo();

            $email = $auth->setEmail();
            $googleId = $auth->getGooglePlusId();
            $googleDisplayName = $auth->getGooglePlusDisplayName();

            /*
             * if user is in database, and has a blue acorn email adress log them in
             * log user in and assign admin status
             *
             */
            if($auth->checkDatabaseForUser($googleId)){

                //direct to dashboard
                $this->headerRedirect('incubate', 'calendar', 'index');
            }

            /*
             * else if not in database, check for blueacorn email adress and make member
             *
             * if this is true, then the user is a first time user with a blue acorn email address,
             * we must add their information to the database and send to index
             *
             */
            elseif($auth->validateNewEmailAddress($email)) {
                $this->_sessionSet('email', $email);
                $this->_sessionSet('googleDisplayName', $googleDisplayName);
                $this->redirect('User','Form','registerAction');
            }

            /*
             * else, return to login page
             */
            else {
                //direct back to login, user is not located in database, and does not have a blue acorn email address
                Core_Model_Session::dangerflash('error', 'Blue Acorn Email addresses only');
                $this->headerRedirect('user','logout','index');
            }
        }

        /*
         * check if the user is logged in,
         * if true : provide them with a sign link with a request url
         * false : provide them with a logout link
         *
         */
        elseif(!$auth->isLoggedIn()) {
            $view->getContent()->setAuthurl($auth->getAuthUrl());
            /*
             * will render the default google login landing page
             */

            $view->render();
        }
        else {
            //if all else fails, the user is logged in but directly tried accessing default login page
            //prompt user to signout or redirect

            $view->render();
        }


    }

}