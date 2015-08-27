<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/25/15
 * Time: 11:27 AM
 *
 *
 * Abstract Admin class, extends incubate abstract class
 * Controllers should extend if they have admin specific actions,
 **/
class Incubate_Controller_Admin extends Incubate_Controller_Abstract
{
    /**
     * Calls parent constructor, which will check for logged in status
     * Calls function to check if user is admin, will redirect otherwise
     **/
    public function __construct()
    {
        parent::__construct();
        $this->_checkIfUserIsAdmin();
    }
}