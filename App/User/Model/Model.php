<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/16/15
 * Time: 2:36 PM
 */
class User_Model_Model extends Core_Model_Abstract
{
    public function __construct()
    {
        $this->_table = 'user';
        parent::__construct();
    }

    public function loadUserByGoogleId($googleId)
    {
        $this->_data = $this->get(array('google_id', '=', $googleId));
        return $this->_data;
    }


    public function makeUserAdmin()
    {
        $this->update($this->_data->id, 'id', array(
            'role' => 'admin'
        ));
    }

    /*
     * essentially logs the user out by ridding the session of its token
     */
    public function logout()
    {
        unset($_SESSION['access_token']);
        Core_Model_Session::set('logged_in', false);
        Core_Model_Session::deleteAll();
    }

    public function checkUserDataForGoogleId($googleId)
    {
        /*
         * check db, for USER table, WHERE google_id = $googleid, return the first
         * set of info  found, store in $_data
         */
        if ($this->loadUserByGoogleId($googleId)) {

            Core_Model_Session::set('user_id', $this->getId());
            Core_Model_Session::set('logged_in', true);
            if ($this->getRole() == 'admin') {
                Core_Model_Session::set('admin_status', true);
            } else {
                Core_Model_Session::set('admin_status', false);
            }
            return true;
        }

        /*
         *
         * even if they are not in the database, store google id in the session,
         * will need to use later if the user must be added to the database.
         */
        Core_Model_Session::set('google_id', $googleId);

        return false;
    }

    /*
     * will return false if data has not been set
     * cheks user
     */
    public function checkUserDataForAdminStatus($googleId)
    {
        $user = $this->get(array('google_id', '=', $googleId));
        if (isset($user['role']) && $user['role'] == 'admin') {
            return true;
        }
        return false;
    }

    public function getUserEmail($studentName)
    {
        if ($user = $this->get(array('name', '=', $studentName))) {
            return $user->email;
        }
        return null;
    }

    public function loadAllByGroup($group)
    {
        $usersByGroup = $this->loadAllBasedOnFields(array('groups', '=', $group));
        return $usersByGroup;
    }

    public function loadAllStudents()
    {
        $allStudents = $this->loadAllBasedOnFields(array('role', '=', 'student'));
        return $allStudents;
    }

    public function loadAllAdmins()
    {
        $allAdmins = $this->loadAllBasedOnFields(array('role','=','admin'));
        return $allAdmins;
    }


    public function setUserIncubationTime()
    {
        $incubationTimer = date('Y-m-d', strtotime($this->getJoined() . "+90 days"));
        $this->setIncubation($incubationTimer);
        return $this;
    }

    public function loadProfile()
    {
        Bootstrap::dispatchEvent('user_load_profile', $this);
        return $this;
    }

}