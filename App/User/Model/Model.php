<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/16/15
 * Time: 2:36 PM
 *
 * User Model responsible for handling all informtion in database table 'user'
 **/
class User_Model_Model extends Core_Model_Abstract
{
    /** Sets table to 'user', instantiates database connection    */
    public function __construct()
    {
        $this->_table = 'user';
        parent::__construct();
    }

    /** Loads a users info by google id,
     *  Used in google signin OAuth procedures
     * @param $googleId google id of user
     * @return $this->_data
     **/
    public function loadUserByGoogleId($googleId)
    {
        $this->_data = $this->get(array('google_id', '=', $googleId));
        return $this->_data;
    }


    /**
     * Sets a users 'role' to 'admin'
     * Dispatched events will delete a users current tags,
     * and their completed courses map
     *
     **/
    public function makeUserAdmin()
    {
        $this->update(array(
            'role' => 'admin'
        ));
        Bootstrap::dispatchEvent('user_delete_after', $this);
    }

    /**
     * Ensures session is over, by setting logged_in status to
     * false, and  ridding the session of its token
     * and deleting any other session data to be sure
     **/
    public function logout()
    {
        unset($_SESSION['access_token']);
        Core_Model_Session::set('logged_in', false);
        Core_Model_Session::deleteAll();
    }

    /**
     * Checks user for google id across database
     *
     * Used in google sign in OAuth procedures
     *
     * If user is in database, session data is set to log user in
     *
     * @param $googleId:  users google id
     * @return bool : returns true if user is in database, returns false otherwise
     **/
    public function checkUserDataForGoogleId($googleId)
    {
        /**
         * check db, for USER table, WHERE google_id = $googleid, return the first
         * set of info  found, loads the user data
         **/
        if ($this->loadUserByGoogleId($googleId)) {

            /** Stores user id in session */
            Core_Model_Session::set('user_id', $this->getId());

            /** Sets logged in status to true */
            Core_Model_Session::set('logged_in', true);

            /** Check for user role */
            if ($this->getRole() == 'admin') {
                /** admin status set to true */
                Core_Model_Session::set('admin_status', true);
            } else {
                /** admin status set to false */
                Core_Model_Session::set('admin_status', false);
            }
            return true;
        }
        /**
         * Even if they are not in the database, store google id in the session,
         * will need to use later if the user must be added to the database.
         **/
        Core_Model_Session::set('google_id', $googleId);
        return false;
    }

    /**
     * Returns a users email, based on name
     * @param $studentName : search database for this name
     * @return users email if located|null otherwise
     **/
    public function getUserEmail($studentName)
    {
        if ($user = $this->get(array('name', '=', $studentName))) {
            return $user->email;
        }
        return null;
    }

    /**
     * Loads all students and profiles
     *
     * foreach student in user table, dispatched events will add additional info
     * like tags, and completed coureses, incubation time, user progress
     *
     * @return array : all students
     **/
    public function loadAllStudents()
    {
        $allStudents = $this->loadAllBasedOnFields(array('role', '=', 'student'));
        foreach ($allStudents as $student) {
            $student->loadProfile();
        }
        return $allStudents;
    }

    /**
     * Gets all user names based on role
     *
     * @param $role : 'admin' || 'student'
     * @return array of user names | null
     **/
    public function getAllUserNames($role)
    {

        if($allData = $this->getAllBasedOnGivenFields(array('role', '=',$role))) {
            foreach($allData as $data) {
                $nameArray[] = $data['name'];
            }
            return $nameArray;
        }
        return null;
    }

    /**
     * Loads all admin users and their information
     *
     * @return array
     **/
    public function loadAllAdmins()
    {
        $allAdmins = $this->loadAllBasedOnFields(array('role','=','admin'));
        return $allAdmins;
    }

    /**
     * Calculates a user expected incubation time
     *
     * @return $this
     */
    public function setUserIncubationTime()
    {
        $incubationTimer = date('Y-m-d', strtotime($this->getJoined() . "+90 days"));
        $this->setIncubation($incubationTimer);
        return $this;
    }

    /**
     * Dispatches events to load additional information on to a user
     *
     * Including tags, completeed lesson, incubation time, user progress
     * @return $this
     **/
    public function loadProfile()
    {
        Bootstrap::dispatchEvent('user_load_profile', $this);
        return $this;
    }

}