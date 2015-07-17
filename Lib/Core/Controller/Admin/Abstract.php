<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/16/15
 * Time: 11:31 AM
 *
 * child class will have ability to create/edit/delete users from database tables,
 */
abstract class Core_Controller_Admin_Abstract extends Core_Controller_Abstract
{
    protected $_db;
    public function __construct()
    {
        //check if this user has permission to be here
        $this->_db = Core_Model_Database::getInstance();
    }

    public function create($table,$fields)
    {
        $admin = Core_Model_Database::getInstance()->insert($table, $fields);
    }

    public function edit()
    {

    }

    public function checkAdminStatus($userPermissionId)
    {

        $permission = $this->_db->get('permission', array('id', '=', $userPermissionId));

        if($permission->count()) {
            $permissionRole = $permission->first()->name;

            if($permissionRole == 'Admin') {
                return true;
            }
        }

        return false;
    }
}