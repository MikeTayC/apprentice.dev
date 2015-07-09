<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/9/15
 * Time: 11:41 AM
 */
class Academy_Model_User
{
    private $_db;

    public function __construct($user = null)
    {
        $this->_db = Core_Model_Database::getInstance();
    }

    public function create($fields = array()) {
        if(!$this->_db->insert('users', $fields)) {
            throw new Exception('Problem creating a new account!');
        }
    }
}