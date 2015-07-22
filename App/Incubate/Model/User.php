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

    public function __construct($user = null)
    {
        $this->_db = Core_Model_Database::getInstance();
    }


    public function createLesson($fields = array()) {
        if(!$this->_db->insert('lesson', $fields)) {
            throw new Exception('Problem creating a new lesson!');
        }
    }

    public function delete()
    {

    }

}