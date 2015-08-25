<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/25/15
 * Time: 11:27 AM
 */
class Incubate_Controller_Admin extends Incubate_Controller_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->_checkIfUserIsAdmin();
    }
}