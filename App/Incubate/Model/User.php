<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/16/15
 * Time: 2:36 PM
 */
class Incubate_Model_User
{
    private $_db;
    private $_data;
    private $_isLoggedIn;

    public function __construct(/** Maybe add a way to check for user */)
    {
        $this->_db = Core_Model_Database::getInstance();

    }

    public function create($table, $fields = array()) {
        if(!$this->_db->insert($table, $fields)) {
            throw new Exception('Problem creating a new lesson!');
        }
    }

    public function checkUserDataForGoogleId($googleId)
    {
        /*
         * check db, for USER table, WHERE google_id = $googleid, return the first
         * set of info  found, store in $_data
         */
        if($this->_data = $this->_db->get('user', array('google_id', '=', $googleId))->first()) {

            return true;
        }
        return false;
    }


    /*
     * will return false if data has not been set
     * cheks user
     */
    public function checkUserDataForAdminStatus()
    {
        if($this->_data->role == 'admin') {
            return true;
        }
        return false;
    }

    public function getAllUserData()
    {
        return $this->_data;
    }
    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }



}