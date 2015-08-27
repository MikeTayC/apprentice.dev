<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/17/15
 * Time: 11:29 AM
 *
 * Abstract parent class of all controllers involved in the incubate app,
 * it extnds the Core Abstract Controller of the framework,
 *
 * Contains wrapper functions to check session variables,
 * Checks for user information regarding logged in status, and admin status.
 * Handles redirection
 *
 * Controllers should extend if they need to check user login status
 **/
abstract class Incubate_Controller_Abstract extends Core_Controller_Abstract
{

    /**
     * Parent constructor automatically checks if user is logged in.
     **/
    public function __construct()
    {
        $this->_checkIfUserIsLoggedIn();
    }

    /**
     * Checks session variable 'logged_in' to see if user is logged in,
     * This should be set at login by User_Model_Model class, see
     * function checkUserDataForGoogleId(). [Called from Core_Model_Auth]
     *
     *
     * If the user isn't logged in, it stores an error in the session and
     * Redirects to the login
     **/
    protected function _checkIfUserIsLoggedIn()
    {
        if(!$this->_sessionGet('logged_in')) {
            $this->_dangerFlash('You are not logged in!');
            $this->headerRedirect('incubate','login','index');
        }
    }

    /**
     * Checks session variable 'admin_status' to see if user is an admin in,
     * This should be set at login by User_Model_Model class, see
     * function checkUserDataForGoogleId(). [Called from Core_Model_Auth]
     * If the user isn' an admin, it stores an error in the session and
     * Set in Core_Model_Auth,
     *
     * Redirects to the calendar index
     **/
    protected function _checkIfUserIsAdmin()
    {
        if(!Core_Model_Session::get('admin_status')) {
            $this->_dangerFlash('Admins Only');
            $this->headerRedirect('schedule','calendar','index');
        }
    }

    /**
     * Checks session for messages,both for success and errors
     **/
    protected function _flashCheck()
    {
        echo Core_Model_Session::dangerFlash('error');
        echo  Core_Model_Session::successFlash('message');
    }


    /**
     * Used to write success messages into session
     *
     * @param $message : string, to be written
     */
    protected function _successFlash($message)
    {
        Core_Model_Session::successFlash('message', $message);
    }


    /**
     * Used to write error messages into session
     *
     * @param $message : string, to be written
     */
    public function _dangerFlash($message)
    {
        Core_Model_Session::dangerFlash('error', $message);
    }

    /**
     * Used explode comma separaed lists into an array
     *
     * @param $list : string a comma sepearated list
     * @return array : exploded list
     **/
    public function explode($list)
    {
        $newArray = explode(',', $list);
        return $newArray;
    }

    /**
     * Function used to check before profile views, only admins, and users specific to
     * a profile can view
     *
     * redirects to user calendar index, if neither
     *
     * @param $userId :  int, user id of profile to check
     */
    public function userProfileCheck($userId)
    {
        if(!$this->_sessionGet('admin_status') && ($this->_sessionGet('user_id') != $userId)) {
                Core_Model_Session::dangerFlash('error', 'You must be an admin to visit another profile');
                $this->headerRedirect('schedule','calendar','index');
            }
        }

    /**
     * Returns an singleton instance of the core request object,
     *
     * For checking post data
     *
     * @return Core_Model_Request|null
     **/
    protected function _getRequest(){
        return Core_Model_Request::getInstance();
    }

    /**
     * Wrapper function that calls the core session object, to return
     * a requested session variable
     *
     * @param $param : string, session variable key
     * @return the session variable value|null
     **/
    protected function _sessionGet($param)
    {
        return Core_Model_Session::get($param);
    }

    /**
     *
     * Wrapper function thats sets session varialbles using the core session object
     *
     * @param $param : string, session variable key
     * @param $value : mixed, session variable to be set
     **/
    protected function _sessionSet($param, $value)
    {
        Core_Model_Session::set($param, $value);
    }

    /**
     *
     * Wrapper function that useses core session object to delete param
     *
     * @param $param: string, session variable key to be deleted
     **/
    protected function _sessionDelete($param)
    {
        Core_Model_Session::delete($param);
    }

    /**
     * Checks id, of user, lesson, or tag to manipulated
     * Calls, them through bootstrap factory
     *
     * @param $id : int, id to be checked
     * @param $string string, to determine which model to check with
     * @return bool: true if id exists | redirects if id does not exist
     */
    protected function _idCheck($id, $string)
    {
        if(!Bootstrap::getModel("{$string}/model")->check($id)) {
            $this->_dangerFlash("Your request does not exist!");
            $this->_thisModuleRedirect('view');
        }
        return true;
    }
}