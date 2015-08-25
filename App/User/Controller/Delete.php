<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 */
class User_Controller_Delete extends Incubate_Controller_Admin
{
    public function idAction($userId)
    {
        $this->_idCheck($userId, 'user');

        Bootstrap::getModel('user/user')->load($userId)->delete();


        $this->_successFlash('User successfully removed');
        $this->_thisModuleRedirect('view');
    }
}