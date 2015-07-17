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

    public function __construct()
    {
        $this->_db = Core_Model_Database::getInstance();
    }
}