<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/17/15
 * Time: 1:02 PM
 */
class Core_Model_Acl
{
    public $perms = array();
    public $userId = 0;
    public $userRoles = array();

    public function __construct($userId = null)
    {
        if(!$userId) {
            $this->userId = floatval($userId);
        }
        else {
            $this->userId = floatval($_SESSION['userId']);
        }

        $this->userRoles = $this->getUserRoles('ids');
        $this->biuldAcl();
    }

    function ACL($userId = null)
    {
        $this->__construct($userId);
    }
}