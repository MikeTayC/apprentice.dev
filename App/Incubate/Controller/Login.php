<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/29/15
 * Time: 1:38 PM
 *
 * Login controller, heavily coupled to authorization classes, which
 * are needed to interact with google OAuth 2.0 login processes
 **/
class Incubate_Controller_Login extends Incubate_Controller_Abstract
{

    /**
     * Need to override parent contstructor, so there are no checks for
     * login status
     **/
    public function __construct(){}

    /**
     * Function is resposible for checking user across database,
     * logging in, validating email address if user is new, redirectoring
     * otherwise
     **/
    public function indexAction()
    {
        /**
         * Default layout is set to false, we want the default google sign
         * in landing page showing that the user must sign in with a blue acorn
         * google plus account
         **/
        $view = $this->loadLayout($default = false);

        /*
         * Checks for error messages when layout is loaded
         */
        $this->_flashCheck();

        /**
         * Instantiate google client with authorization class and user model class
         **/
        $googleClient = new Google_Client();
        $user = Bootstrap::getModel('user/model');
        $auth = new Core_Model_Auth($user, $googleClient);

        /**
         * Call to auth model to make sure the redirect code is set in the get request,
         * routed from sign in url, if url redirect code is not set, moves on to check
         * if the user is logged in
         **/
        if($auth->checkRedirectCode()) {

            /**
             * Set current user information from google plus for access
             * Stores in local variables
             **/
            $auth->setGooglePlusInfo();
            $email = $auth->setEmail();
            $googleId = $auth->getGooglePlusId();
            $googleDisplayName = $auth->getGooglePlusDisplayName();

            /**
             * If user is in database, and has a blue acorn email adress log them in
             * log user in and assign admin status
             *
             **/
            if($auth->checkDatabaseForUser($googleId)){

                //direct to dashboard
                $this->headerRedirect('incubate', 'calendar', 'index');
            }

            /*
             * Else if not in database, check for blueacorn email adress and make member
             *
             * If this is true, then the user is a first time user with a blue acorn email address,
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