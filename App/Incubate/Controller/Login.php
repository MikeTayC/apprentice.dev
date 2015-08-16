<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/29/15
 * Time: 1:38 PM
 *
 */
class Incubate_Controller_Login extends Core_Controller_Abstract
{
    public function indexAction()
    {
        /*
         * Default layout is set to false, we want the default google sign in landing page showing that the user must
         * sign in with a blue acorn google plus account
         */
        $view = $this->loadLayout($default = false);

        /*
         * instantiate google client and our authorization class and user model class
         */
        $googleClient = new Google_Client();
        $user = new Incubate_Model_User();
        $auth = new Core_Model_Auth($user, $googleClient);

        /*
         * call to auth model to make sure the redirect code is set in the get request, routed from sign in url
         * TODO FINISH LOGIC FOR CHECKING DATABASE USER INFO AGAINST USER LOGIN INFORMATION
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

                //assign the admin status to true or false
                $auth->assignAdminStatus();

                //direct to dashboard
                $this->headerRedirect('incubate', 'index', 'index');
            }

            /*
             * else if not in database, check for blueacorn email adress and make member
             *
             * if this is true, then the user is a first time user with a blue acorn email address,
             * we must add their information to the database and send to index
             *
             */
            elseif($auth->validateNewEmailAddress($email)) {

                /*
                 * auth will use the user model to add to database
                 */

                $auth->addNewUser($googleId, $googleDisplayName, $email);

                //assigns admin status to true or false
                $auth->assignAdminStatus();

                //direct to dashboard
                $this->redirect('incubate','register','indexAction', $googleId);
            }

            /*
             * else, return to login page
             */
            else {
                //direct back to login, user is not located in database, and does not have a blue acorn email address
                $this->redirect('Core','Error', 'errorAction');
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
            echo Core_Model_Session::flash('error');
            $view->render();
        }
        else {
            //if all else fails, the user is logged in but directly tried accessing default login page
            //prompt user to signout or redirect
            echo Core_Model_Session::flash('error');
            $view->render();
        }


    }

}